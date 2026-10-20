<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
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
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->uploadedImage($request, 'image', 'posts');
        $data['user_id'] = $request->user()->id;

        $post = Post::create($data);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'L\'article « '.$post->title.' » a été créé.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', [
            'post' => $post,
            'categories' => Category::ofType('post')->orderBy('name')->pluck('name', 'id'),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $data = $this->validated($request, $post);
        $data['image'] = $this->uploadedImage($request, 'image', 'posts', $post->image);

        $post->update($data);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'L\'article « '.$post->title.' » a été mis à jour.');
    }

    public function destroy(Post $post)
    {
        $this->deleteImage($post->image);
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
        ], [], [
            'title' => 'titre',
            'body' => 'contenu',
            'status' => 'statut',
        ]);

        // Les mots-clés sont saisis séparés par des virgules.
        $data['tags'] = collect(explode(',', (string) ($data['tags'] ?? '')))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return $data;
    }
}
