<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::published()
            ->with(['category', 'author'])
            ->withCount('approvedComments')
            ->when($request->filled('categorie'), function ($query) use ($request) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('categorie')));
            })
            ->when($request->filled('tag'), function ($query) use ($request) {
                $query->where('tags', 'like', '%'.$request->string('tag').'%');
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($q) => $q->where('title', 'like', $term)->orWhere('body', 'like', $term));
            })
            ->latest('published_at')
            ->paginate(6)
            ->withQueryString();

        return view('site.posts.index', array_merge($this->sidebar(), [
            'posts' => $posts,
            'layout' => $request->string('vue')->toString() === 'liste' ? 'list' : 'grid',
        ]));
    }

    public function show(Post $post)
    {
        abort_if($post->status !== 'published', 404);

        $post->increment('views');

        return view('site.posts.show', array_merge($this->sidebar(), [
            'post' => $post->load(['category', 'author', 'approvedComments', 'gallery']),
            'previous' => Post::published()->where('id', '<', $post->id)->latest('id')->first(),
            'next' => Post::published()->where('id', '>', $post->id)->oldest('id')->first(),
        ]));
    }

    public function comment(Request $request, Post $post)
    {
        abort_if(! $post->allow_comments, 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'body' => ['required', 'string', 'min:5', 'max:3000'],
        ], [], [
            'name' => 'nom',
            'email' => 'adresse e-mail',
            'body' => 'commentaire',
        ]);

        $post->comments()->create($data);

        return back()->with('success', 'Merci ! Votre commentaire sera publié après modération.');
    }

    /**
     * Shared sidebar widgets for the news section.
     */
    private function sidebar(): array
    {
        $tags = Post::published()->whereNotNull('tags')->pluck('tags')->flatten()->unique()->take(12)->values();

        return [
            'sidebarCategories' => Category::active()->ofType('post')->withCount('posts')->orderBy('sort_order')->get(),
            'sidebarRecent' => Post::published()->latest('published_at')->take(4)->get(),
            'sidebarTags' => $tags,
        ];
    }
}
