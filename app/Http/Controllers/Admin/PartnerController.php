<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.partners.index', [
            'partners' => Partner::orderBy('sort_order')->paginate(24),
        ]);
    }

    public function create()
    {
        return view('admin.partners.form', ['partner' => new Partner(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['logo'] = $this->uploadedImage($request, 'logo', 'partners');

        Partner::create($data);

        return redirect()->route('admin.partners.index')->with('success', 'Le partenaire a été ajouté.');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.form', ['partner' => $partner]);
    }

    public function update(Request $request, Partner $partner)
    {
        $data = $this->validated($request);
        $data['logo'] = $this->uploadedImage($request, 'logo', 'partners', $partner->logo);

        $partner->update($data);

        return redirect()->route('admin.partners.index')->with('success', 'Le partenaire a été mis à jour.');
    }

    public function destroy(Partner $partner)
    {
        $this->deleteImage($partner->logo);
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Le partenaire a été supprimé.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'image', 'max:4096'],
        ], [], ['name' => 'nom']);
    }
}
