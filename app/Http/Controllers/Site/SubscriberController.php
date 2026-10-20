<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:160'],
        ], [], ['email' => 'adresse e-mail']);

        Subscriber::updateOrCreate(
            ['email' => $data['email']],
            ['ip_address' => $request->ip(), 'is_active' => true]
        );

        // Clé dédiée : le formulaire vit dans le pied de page, présent sur toutes
        // les pages, y compris celles qui n'affichent pas les messages génériques.
        return back()->with('newsletter_success', 'Merci ! Vous êtes inscrit à notre lettre d\'information.');
    }
}
