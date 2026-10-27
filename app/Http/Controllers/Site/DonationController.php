<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Cause;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DonationController extends Controller
{
    public function create(Request $request)
    {
        return view('site.donations.create', [
            'causes' => Cause::published()->orderBy('title')->get(['id', 'title']),
            'selectedCause' => $request->integer('cause') ?: null,
            'presetAmount' => $request->integer('montant') ?: null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cause_id' => ['nullable', Rule::exists('causes', 'id')],
            'donor_name' => ['required', 'string', 'max:120'],
            'donor_email' => ['required', 'email', 'max:160'],
            // Le numéro Mobile Money sert à rapprocher le paiement reçu de la promesse de don.
            'donor_phone' => [
                Rule::requiredIf(array_key_exists((string) $request->input('payment_method'), Donation::MOBILE_MONEY)),
                'nullable', 'string', 'max:40',
            ],
            'donor_address' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:100', 'max:100000000'],
            'payment_method' => ['required', Rule::in(Donation::PUBLIC_METHODS)],
            'fund_type' => ['nullable', 'string', 'max:120'],
            'message' => ['nullable', 'string', 'max:2000'],
            'is_anonymous' => ['nullable', 'boolean'],
        ], [], [
            'donor_name' => 'nom',
            'donor_email' => 'adresse e-mail',
            'donor_phone' => 'numéro Mobile Money',
            'amount' => 'montant',
        ]);

        $data['is_anonymous'] = $request->boolean('is_anonymous');
        $data['status'] = 'pending';

        $donation = Donation::create($data);
        $donation->sendReceipt();

        return redirect()
            ->route('donations.thanks', $donation->reference)
            ->with('success', 'Merci ! Votre promesse de don a bien été enregistrée.');
    }

    public function thanks(Donation $donation)
    {
        return view('site.donations.thanks', [
            'donation' => $donation->load('cause'),
        ]);
    }
}
