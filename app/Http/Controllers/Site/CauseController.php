<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Cause;
use Illuminate\Http\Request;

class CauseController extends Controller
{
    public function index(Request $request)
    {
        $causes = Cause::published()
            ->with('category')
            ->when($request->filled('categorie'), function ($query) use ($request) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('categorie')));
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($q) => $q->where('title', 'like', $term)->orWhere('excerpt', 'like', $term));
            })
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('site.causes.index', [
            'causes' => $causes,
            'categories' => Category::active()->ofType('cause')->withCount('causes')->orderBy('sort_order')->get(),
        ]);
    }

    public function show(Cause $cause)
    {
        abort_if($cause->status === 'draft', 404);

        $cause->increment('views');

        return view('site.causes.show', [
            'cause' => $cause->load('category'),
            'recentDonations' => $cause->donations()->completed()->latest()->take(5)->get(),
            'related' => Cause::published()
                ->where('id', '!=', $cause->id)
                ->when($cause->category_id, fn ($q) => $q->where('category_id', $cause->category_id))
                ->latest()
                ->take(3)
                ->get(),
            'categories' => Category::active()->ofType('cause')->withCount('causes')->orderBy('sort_order')->get(),
        ]);
    }
}
