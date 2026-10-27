<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Cause;
use App\Models\Event;
use App\Models\Page;
use App\Models\Post;

class SeoController extends Controller
{
    /**
     * XML sitemap listing every public page of the site.
     */
    public function sitemap()
    {
        $static = collect([
            'home', 'about', 'team', 'testimonials', 'faq', 'causes.index', 'events.index',
            'posts.index', 'donations.create', 'volunteers.create', 'contact.create',
        ])->map(fn ($name) => ['loc' => route($name), 'lastmod' => null]);

        $entries = $static
            ->merge(Cause::where('status', '!=', 'draft')->get(['slug', 'updated_at'])
                ->map(fn ($cause) => ['loc' => route('causes.show', $cause), 'lastmod' => $cause->updated_at]))
            ->merge(Event::where('status', '!=', 'draft')->get(['slug', 'updated_at'])
                ->map(fn ($event) => ['loc' => route('events.show', $event), 'lastmod' => $event->updated_at]))
            ->merge(Post::published()->get(['slug', 'updated_at'])
                ->map(fn ($post) => ['loc' => route('posts.show', $post), 'lastmod' => $post->updated_at]))
            ->merge(Page::where('is_active', true)->get(['slug', 'updated_at'])
                ->map(fn ($page) => ['loc' => route('pages.show', $page), 'lastmod' => $page->updated_at]));

        return response()
            ->view('site.seo.sitemap', ['entries' => $entries])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * Crawling rules, pointing search engines at the sitemap.
     */
    public function robots()
    {
        $lines = app()->isProduction()
            ? ['User-agent: *', 'Disallow: /admin', 'Disallow: /dashboard', 'Disallow: /settings', 'Disallow: /don/', '', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n")->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
