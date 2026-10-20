<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->string('periode')->toString() ?: 'upcoming';

        $events = Event::published()
            ->when($filter === 'upcoming', fn ($q) => $q->upcoming()->orderBy('starts_at'))
            ->when($filter === 'past', fn ($q) => $q->past()->orderByDesc('starts_at'))
            ->when($filter === 'all', fn ($q) => $q->orderByDesc('starts_at'))
            ->paginate(6)
            ->withQueryString();

        return view('site.events.index', [
            'events' => $events,
            'filter' => $filter,
        ]);
    }

    public function show(Event $event)
    {
        abort_if($event->status === 'draft', 404);

        return view('site.events.show', [
            'event' => $event,
            'upcoming' => Event::published()->upcoming()->where('id', '!=', $event->id)->orderBy('starts_at')->take(3)->get(),
        ]);
    }

    public function register(Request $request, Event $event)
    {
        abort_if($event->status !== 'published', 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'seats' => ['required', 'integer', 'min:1', 'max:20'],
            'message' => ['nullable', 'string', 'max:1000'],
        ], [], [
            'name' => 'nom',
            'email' => 'adresse e-mail',
            'seats' => 'nombre de places',
        ]);

        if ($event->seats_left !== null && $data['seats'] > $event->seats_left) {
            return back()
                ->withInput()
                ->withErrors(['seats' => 'Il ne reste que '.$event->seats_left.' place(s) disponible(s).']);
        }

        $event->registrations()->create($data);

        return back()->with('success', 'Votre inscription a été enregistrée, nous vous contacterons rapidement.');
    }
}
