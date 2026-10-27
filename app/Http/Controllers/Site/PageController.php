<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Cause;
use App\Models\Donation;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Partner;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\Volunteer;

class PageController extends Controller
{
    public function about()
    {
        return view('site.about', [
            'stats' => [
                'donations' => (float) Donation::completed()->sum('amount'),
                'causes' => Cause::published()->count(),
                'beneficiaries' => (int) setting('stat_beneficiaries', 13700),
                'volunteers' => Volunteer::where('status', 'approved')->count() + TeamMember::active()->count(),
            ],
        ]);
    }

    public function team()
    {
        return view('site.team', [
            'members' => TeamMember::active()->orderBy('sort_order')->get(),
        ]);
    }

    public function testimonials()
    {
        return view('site.testimonials', [
            'testimonials' => Testimonial::active()->orderBy('sort_order')->get(),
            'partners' => Partner::active()->orderBy('sort_order')->get(),
        ]);
    }

    public function faq()
    {
        return view('site.faq', [
            'faqs' => Faq::active()->orderBy('sort_order')->get()->groupBy(fn ($faq) => $faq->category ?: 'Général'),
        ]);
    }

    public function show(Page $page)
    {
        abort_if(! $page->is_active, 404);

        return view('site.page', ['page' => $page]);
    }
}
