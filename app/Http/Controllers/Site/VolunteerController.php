<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VolunteerController extends Controller
{
    public function create()
    {
        return view('site.volunteer');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:255'],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'interest' => ['required', Rule::in(array_keys(Volunteer::INTERESTS))],
            'availability' => ['nullable', 'string', 'max:160'],
            'message' => ['nullable', 'string', 'max:3000'],
        ], [], [
            'name' => 'nom',
            'email' => 'adresse e-mail',
            'interest' => 'domaine d\'engagement',
        ]);

        Volunteer::create($data);

        return back()->with('success', 'Votre candidature est enregistrée. Bienvenue dans l\'aventure !');
    }
}
