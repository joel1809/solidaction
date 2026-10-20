<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $registrations = EventRegistration::query()
            ->with('event')
            ->when($request->filled('event'), fn ($query) => $query->where('event_id', $request->integer('event')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($q) => $q->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.registrations.index', [
            'registrations' => $registrations,
            'events' => Event::orderByDesc('starts_at')->pluck('title', 'id'),
        ]);
    }

    public function update(Request $request, EventRegistration $registration)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(EventRegistration::STATUSES))],
        ]);

        $registration->update($data);

        return back()->with('success', 'L\'inscription de '.$registration->name.' est « '.$registration->status_label.' ».');
    }

    public function destroy(EventRegistration $registration)
    {
        $name = $registration->name;
        $registration->delete();

        return back()->with('success', 'L\'inscription de '.$name.' a été supprimée.');
    }
}
