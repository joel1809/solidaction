<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VolunteerController extends Controller
{
    public function index(Request $request)
    {
        $volunteers = Volunteer::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('interest'), fn ($query) => $query->where('interest', $request->string('interest')))
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($q) => $q->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.volunteers.index', [
            'volunteers' => $volunteers,
            'counts' => Volunteer::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    public function show(Volunteer $volunteer)
    {
        return view('admin.volunteers.show', ['volunteer' => $volunteer]);
    }

    public function update(Request $request, Volunteer $volunteer)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Volunteer::STATUSES))],
        ]);

        $volunteer->update($data);

        return back()->with('success', 'La candidature de '.$volunteer->name.' est « '.$volunteer->status_label.' ».');
    }

    public function destroy(Volunteer $volunteer)
    {
        $name = $volunteer->name;
        $volunteer->delete();

        return redirect()
            ->route('admin.volunteers.index')
            ->with('success', 'La candidature de '.$name.' a été supprimée.');
    }
}
