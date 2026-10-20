<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Cause;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CauseController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        $causes = Cause::query()
            ->with('category')
            ->withCount('donations')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->string('q').'%');
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.causes.index', [
            'causes' => $causes,
            'categories' => Category::ofType('cause')->orderBy('name')->pluck('name', 'id'),
        ]);
    }

    public function create()
    {
        return view('admin.causes.form', [
            'cause' => new Cause(['status' => 'published']),
            'categories' => Category::ofType('cause')->orderBy('name')->pluck('name', 'id'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->uploadedImage($request, 'image', 'causes');

        $cause = Cause::create($data);

        return redirect()
            ->route('admin.causes.index')
            ->with('success', 'La cause « '.$cause->title.' » a été créée.');
    }

    public function edit(Cause $cause)
    {
        return view('admin.causes.form', [
            'cause' => $cause,
            'categories' => Category::ofType('cause')->orderBy('name')->pluck('name', 'id'),
        ]);
    }

    public function update(Request $request, Cause $cause)
    {
        $data = $this->validated($request, $cause);
        $data['image'] = $this->uploadedImage($request, 'image', 'causes', $cause->image);

        $cause->update($data);

        return redirect()
            ->route('admin.causes.index')
            ->with('success', 'La cause « '.$cause->title.' » a été mise à jour.');
    }

    public function destroy(Cause $cause)
    {
        $this->deleteImage($cause->image);
        $title = $cause->title;
        $cause->delete();

        return redirect()
            ->route('admin.causes.index')
            ->with('success', 'La cause « '.$title.' » a été supprimée. Les dons associés sont conservés.');
    }

    private function validated(Request $request, ?Cause $cause = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('causes', 'slug')->ignore($cause)],
            'category_id' => ['nullable', Rule::exists('categories', 'id')],
            'excerpt' => ['nullable', 'string', 'max:600'],
            'description' => ['nullable', 'string'],
            'goal_amount' => ['required', 'numeric', 'min:0'],
            'deadline' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:160'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'published', 'closed'])],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:4096'],
        ], [], [
            'title' => 'titre',
            'goal_amount' => 'objectif de collecte',
            'status' => 'statut',
        ]);
    }
}
