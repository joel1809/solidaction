<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.pages.index', ['pages' => Page::orderBy('title')->paginate(20)]);
    }

    public function create()
    {
        return view('admin.pages.form', ['page' => new Page(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->uploadedImage($request, 'image', 'pages');

        $page = Page::create($data);

        return redirect()->route('admin.pages.index')->with('success', 'La page « '.$page->title.' » a été créée.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.form', ['page' => $page]);
    }

    public function update(Request $request, Page $page)
    {
        $data = $this->validated($request, $page);
        $data['image'] = $this->uploadedImage($request, 'image', 'pages', $page->image);

        $page->update($data);

        return redirect()->route('admin.pages.index')->with('success', 'La page « '.$page->title.' » a été mise à jour.');
    }

    public function destroy(Page $page)
    {
        $this->deleteImage($page->image);
        $title = $page->title;
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'La page « '.$title.' » a été supprimée.');
    }

    private function validated(Request $request, ?Page $page = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('pages', 'slug')->ignore($page)],
            'content' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:4096'],
        ], [], ['title' => 'titre', 'content' => 'contenu']);
    }
}
