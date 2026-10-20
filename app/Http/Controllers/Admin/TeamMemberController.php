<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeamMemberController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        $members = TeamMember::query()
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->string('q').'%'))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.team.index', ['members' => $members]);
    }

    public function create()
    {
        return view('admin.team.form', ['member' => new TeamMember(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['photo'] = $this->uploadedImage($request, 'photo', 'team');

        $member = TeamMember::create($data);

        return redirect()
            ->route('admin.team.index')
            ->with('success', $member->name.' a été ajouté(e) à l\'équipe.');
    }

    public function edit(TeamMember $member)
    {
        return view('admin.team.form', ['member' => $member]);
    }

    public function update(Request $request, TeamMember $member)
    {
        $data = $this->validated($request, $member);
        $data['photo'] = $this->uploadedImage($request, 'photo', 'team', $member->photo);

        $member->update($data);

        return redirect()
            ->route('admin.team.index')
            ->with('success', 'La fiche de '.$member->name.' a été mise à jour.');
    }

    public function destroy(TeamMember $member)
    {
        $this->deleteImage($member->photo);
        $name = $member->name;
        $member->delete();

        return redirect()
            ->route('admin.team.index')
            ->with('success', $name.' a été retiré(e) de l\'équipe.');
    }

    private function validated(Request $request, ?TeamMember $member = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', Rule::unique('team_members', 'slug')->ignore($member)],
            'role' => ['nullable', 'string', 'max:120'],
            'bio' => ['nullable', 'string', 'max:1500'],
            'email' => ['nullable', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ], [], ['name' => 'nom', 'role' => 'fonction']);
    }
}
