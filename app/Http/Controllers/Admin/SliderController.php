<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.sliders.index', [
            'sliders' => Slider::orderBy('sort_order')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.sliders.form', ['slider' => new Slider(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->uploadedImage($request, 'image', 'sliders');

        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('success', 'La diapositive a été ajoutée.');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.form', ['slider' => $slider]);
    }

    public function update(Request $request, Slider $slider)
    {
        $data = $this->validated($request);
        $data['image'] = $this->uploadedImage($request, 'image', 'sliders', $slider->image);

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'La diapositive a été mise à jour.');
    }

    public function destroy(Slider $slider)
    {
        $this->deleteImage($slider->image);
        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'La diapositive a été supprimée.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'subtitle' => ['nullable', 'string', 'max:160'],
            'title' => ['required', 'string', 'max:160'],
            'text' => ['nullable', 'string', 'max:400'],
            'button_text' => ['nullable', 'string', 'max:60'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'button2_text' => ['nullable', 'string', 'max:60'],
            'button2_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:6144'],
            'focus_x' => ['nullable', 'integer', 'between:0,100', 'required_with:focus_y'],
            'focus_y' => ['nullable', 'integer', 'between:0,100', 'required_with:focus_x'],
        ], [], ['title' => 'titre', 'focus_x' => 'point focal', 'focus_y' => 'point focal']);
    }
}
