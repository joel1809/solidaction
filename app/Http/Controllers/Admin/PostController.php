<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostMedia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        $posts = Post::query()
            ->with(['category', 'author'])
            ->withCount('comments')
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.posts.index', [
            'posts' => $posts,
            'categories' => Category::ofType('post')->orderBy('name')->pluck('name', 'id'),
        ]);
    }

    public function create()
    {
        return view('admin.posts.form', [
            'post' => new Post(['status' => 'draft', 'allow_comments' => true]),
            'categories' => Category::ofType('post')->orderBy('name')->pluck('name', 'id'),
            'gallery' => collect(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->uploadedImage($request, 'image', 'posts');
        $data['user_id'] = $request->user()->id;

        $post = Post::create($data);
        $this->syncGallery($request, $post);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'L\'article « '.$post->title.' » a été créé.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', [
            'post' => $post,
            'categories' => Category::ofType('post')->orderBy('name')->pluck('name', 'id'),
            'gallery' => $post->gallery()->get(),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $data = $this->validated($request, $post);
        $data['image'] = $this->uploadedImage($request, 'image', 'posts', $post->image);

        $post->update($data);
        $this->syncGallery($request, $post);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'L\'article « '.$post->title.' » a été mis à jour.');
    }

    public function destroy(Post $post)
    {
        $this->deleteImage($post->image);
        $post->gallery->each(fn (PostMedia $item) => $this->deleteImage($item->path));
        $title = $post->title;
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'L\'article « '.$title.' » a été supprimé.');
    }

    private function validated(Request $request, ?Post $post = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('posts', 'slug')->ignore($post)],
            'category_id' => ['nullable', Rule::exists('categories', 'id')],
            'excerpt' => ['nullable', 'string', 'max:600'],
            'body' => ['nullable', 'string'],
            'tags' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'is_featured' => ['nullable', 'boolean'],
            'allow_comments' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'max:4096'],
            'gallery_files' => ['nullable', 'array', 'max:20'],
            'gallery_files.*' => ['file', 'mimes:jpg,jpeg,png,webp,gif,mp4,webm,mov', 'max:40960'],
            'gallery_videos' => ['nullable', 'string', 'max:3000', function (string $attribute, mixed $value, \Closure $fail) {
                foreach ($this->videoLinks($value) as $link) {
                    if (! PostMedia::isSupportedEmbed($link)) {
                        $fail('Le lien « '.$link.' » n\'est pas une vidéo YouTube ou Vimeo.');
                    }
                }
            }],
            'gallery' => ['nullable', 'array'],
            'gallery.*.caption' => ['nullable', 'string', 'max:255'],
            'gallery.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ], [], [
            'title' => 'titre',
            'body' => 'contenu',
            'status' => 'statut',
            'gallery_files.*' => 'fichier de la galerie',
            'gallery_videos' => 'liens vidéo',
        ]);

        unset($data['gallery_files'], $data['gallery_videos'], $data['gallery']);

        // Les mots-clés sont saisis séparés par des virgules.
        $data['tags'] = collect(explode(',', (string) ($data['tags'] ?? '')))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return $data;
    }

    /**
     * Met à jour la galerie : légendes, ordre et retraits des éléments existants,
     * puis ajout des fichiers envoyés et des liens YouTube / Vimeo saisis.
     */
    private function syncGallery(Request $request, Post $post): void
    {
        $changes = $request->input('gallery', []);

        foreach ($post->gallery()->get() as $item) {
            $change = $changes[$item->id] ?? null;

            if ($change === null) {
                continue;
            }

            if (! empty($change['remove'])) {
                $this->deleteImage($item->path);
                $item->delete();

                continue;
            }

            $item->update([
                'caption' => $change['caption'] ?? null,
                'sort_order' => (int) ($change['sort_order'] ?? $item->sort_order),
            ]);
        }

        $order = (int) $post->gallery()->max('sort_order');

        foreach ($request->file('gallery_files', []) as $file) {
            $post->gallery()->create([
                'type' => str_starts_with((string) $file->getMimeType(), 'video/') ? 'video' : 'image',
                'path' => $file->store('uploads/posts/gallery', 'public'),
                'sort_order' => ++$order,
            ]);
        }

        foreach ($this->videoLinks($request->input('gallery_videos')) as $link) {
            $youtube = PostMedia::youtubeId($link);

            $post->gallery()->create([
                'type' => 'video',
                // Forme « watch » reconnue par la visionneuse du site.
                'url' => $youtube ? 'https://www.youtube.com/watch?v='.$youtube : $link,
                'sort_order' => ++$order,
            ]);
        }
    }

    /**
     * Liens vidéo saisis un par ligne.
     */
    private function videoLinks(?string $value): array
    {
        return collect(preg_split('/\R/', (string) $value))
            ->map(fn (string $line) => trim($line))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
