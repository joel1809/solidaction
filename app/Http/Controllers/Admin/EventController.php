<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        $events = Event::query()
            ->withCount('registrations')
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->string('periode')->toString() === 'upcoming', fn ($query) => $query->upcoming())
            ->when($request->string('periode')->toString() === 'past', fn ($query) => $query->past())
            ->orderByDesc('starts_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.events.index', ['events' => $events]);
    }

    public function create()
    {
        return view('admin.events.form', [
            'event' => new Event(['status' => 'published', 'organizer' => setting('site_name', 'Solid’Action')]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->uploadedImage($request, 'image', 'events');

        $event = Event::create($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'L\'événement « '.$event->title.' » a été créé.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.form', [
            'event' => $event->loadCount('registrations'),
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $data = $this->validated($request, $event);
        $data['image'] = $this->uploadedImage($request, 'image', 'events', $event->image);

        $event->update($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'L\'événement « '.$event->title.' » a été mis à jour.');
    }

    public function destroy(Event $event)
    {
        $this->deleteImage($event->image);
        $title = $event->title;
        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'L\'événement « '.$title.' » et ses inscriptions ont été supprimés.');
    }

    private function validated(Request $request, ?Event $event = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('events', 'slug')->ignore($event)],
            'excerpt' => ['nullable', 'string', 'max:600'],
            'description' => ['nullable', 'string'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'location' => ['nullable', 'string', 'max:160'],
            'address' => ['nullable', 'string', 'max:255'],
            'organizer' => ['nullable', 'string', 'max:120'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'ticket_price' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['draft', 'published', 'cancelled'])],
            'is_featured' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:4096'],
        ], [], [
            'title' => 'titre',
            'starts_at' => 'date de début',
            'ends_at' => 'date de fin',
            'status' => 'statut',
        ]);
    }
}
