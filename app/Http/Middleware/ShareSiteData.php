<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareSiteData
{
    /**
     * Share the data every public layout needs (menus, footer widgets).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->is('admin', 'admin/*')) {
            View::composer(['layouts.site', 'partials.site.*'], function ($view) {
                $view->with([
                    'footerPosts' => Post::published()->latest('published_at')->take(3)->get(['title', 'slug', 'image', 'published_at']),
                    'footerCategories' => Category::active()->ofType('cause')->take(5)->get(['name', 'slug']),
                ]);
            });
        }

        return $next($request);
    }
}
