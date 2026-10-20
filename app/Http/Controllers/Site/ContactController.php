<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create()
    {
        return view('site.contact');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'min:10', 'max:4000'],
        ], [], [
            'name' => 'nom',
            'email' => 'adresse e-mail',
            'message' => 'message',
        ]);

        ContactMessage::create($data);

        // Le formulaire existe aussi dans le panneau latéral, affiché sur toutes les
        // pages : la redirection vers la page Contact garantit un retour visible.
        return redirect()
            ->route('contact.create')
            ->with('success', 'Merci pour votre message, notre équipe vous répondra sous 48 heures.');
    }
}
