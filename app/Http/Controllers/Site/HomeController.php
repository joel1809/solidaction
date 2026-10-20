<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Cause;
use App\Models\Donation;
use App\Models\Event;
use App\Models\Faq;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Slider;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\Volunteer;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCause = Cause::published()->featured()->latest()->first()
            ?? Cause::published()->latest()->first();

        return view('site.home', [
            'sliders' => Slider::active()->orderBy('sort_order')->get(),
            'featuredCause' => $featuredCause,
            'causes' => Cause::published()->with('category')->orderBy('sort_order')->latest()->take(6)->get(),
            'events' => Event::published()->upcoming()->orderBy('starts_at')->take(4)->get(),
            'posts' => Post::published()->with(['category', 'author'])->withCount('approvedComments')->latest('published_at')->take(3)->get(),
            'team' => TeamMember::active()->orderBy('sort_order')->take(4)->get(),
            'testimonials' => Testimonial::active()->orderBy('sort_order')->get(),
            'partners' => Partner::active()->orderBy('sort_order')->get(),
            'faqs' => Faq::active()->orderBy('sort_order')->take(5)->get(),
            'stats' => [
                'donations' => (float) Donation::completed()->sum('amount'),
                'causes' => Cause::published()->count(),
                'beneficiaries' => (int) setting('stat_beneficiaries', 13700),
                'volunteers' => Volunteer::where('status', 'approved')->count() + TeamMember::active()->count(),
            ],
        ]);
    }
}
