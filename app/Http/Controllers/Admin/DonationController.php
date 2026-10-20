<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cause;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $donations = $this->filtered($request)
            ->with('cause')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.donations.index', [
            'donations' => $donations,
            'causes' => Cause::orderBy('title')->pluck('title', 'id'),
            'totals' => [
                'filtered' => (float) $this->filtered($request)->sum('amount'),
                'completed' => (float) Donation::completed()->sum('amount'),
                'pending' => (float) Donation::where('status', 'pending')->sum('amount'),
            ],
        ]);
    }

    public function create()
    {
        return view('admin.donations.form', [
            'donation' => new Donation(['status' => 'completed', 'frequency' => 'one_time', 'payment_method' => 'transfer']),
            'causes' => Cause::orderBy('title')->pluck('title', 'id'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['paid_at'] = $data['status'] === 'completed' ? ($data['paid_at'] ?? now()) : null;

        $donation = Donation::create($data);

        return redirect()
            ->route('admin.donations.show', $donation)
            ->with('success', 'Le don '.$donation->reference.' a été enregistré.');
    }

    public function show(Donation $donation)
    {
        return view('admin.donations.show', [
            'donation' => $donation->load('cause'),
            'history' => Donation::where('donor_email', $donation->donor_email)
                ->where('id', '!=', $donation->id)
                ->latest()
                ->take(10)
                ->get(),
        ]);
    }

    public function edit(Donation $donation)
    {
        return view('admin.donations.form', [
            'donation' => $donation,
            'causes' => Cause::orderBy('title')->pluck('title', 'id'),
        ]);
    }

    public function update(Request $request, Donation $donation)
    {
        $data = $this->validated($request, $donation);

        if ($data['status'] === 'completed' && blank($donation->paid_at) && blank($data['paid_at'] ?? null)) {
            $data['paid_at'] = now();
        }

        $donation->update($data);

        return redirect()
            ->route('admin.donations.show', $donation)
            ->with('success', 'Le don '.$donation->reference.' a été mis à jour.');
    }

    /**
     * Quick status change from the list screen.
     */
    public function updateStatus(Request $request, Donation $donation)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Donation::STATUSES))],
        ]);

        $donation->update([
            'status' => $data['status'],
            'paid_at' => $data['status'] === 'completed' ? ($donation->paid_at ?? now()) : null,
        ]);

        return back()->with('success', 'Le don '.$donation->reference.' est désormais « '.$donation->status_label.' ».');
    }

    public function destroy(Donation $donation)
    {
        $reference = $donation->reference;
        $donation->delete();

        return redirect()
            ->route('admin.donations.index')
            ->with('success', 'Le don '.$reference.' a été supprimé.');
    }

    /**
     * CSV export of the current filter selection.
     */
    public function export(Request $request): StreamedResponse
    {
        $donations = $this->filtered($request)->with('cause')->latest()->get();
        $filename = 'dons-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($donations) {
            $handle = fopen('php://output', 'w');

            // BOM pour une ouverture correcte dans Excel (accents).
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Référence', 'Date', 'Donateur', 'E-mail', 'Téléphone', 'Cause', 'Montant', 'Fréquence', 'Moyen', 'Statut'], ';');

            foreach ($donations as $donation) {
                fputcsv($handle, [
                    $donation->reference,
                    $donation->created_at->format('d/m/Y H:i'),
                    $donation->donor_name,
                    $donation->donor_email,
                    $donation->donor_phone,
                    $donation->cause->title ?? 'Fonds libre',
                    number_format((float) $donation->amount, 2, ',', ''),
                    $donation->frequency_label,
                    $donation->method_label,
                    $donation->status_label,
                ], ';');
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function filtered(Request $request)
    {
        return Donation::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($q) => $q->where('donor_name', 'like', $term)
                    ->orWhere('donor_email', 'like', $term)
                    ->orWhere('reference', 'like', $term));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('frequency'), fn ($query) => $query->where('frequency', $request->string('frequency')))
            ->when($request->filled('cause'), fn ($query) => $query->where('cause_id', $request->integer('cause')))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('created_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('created_at', '<=', $request->date('to')));
    }

    private function validated(Request $request, ?Donation $donation = null): array
    {
        return $request->validate([
            'cause_id' => ['nullable', Rule::exists('causes', 'id')],
            'donor_name' => ['required', 'string', 'max:120'],
            'donor_email' => ['required', 'email', 'max:160'],
            'donor_phone' => ['nullable', 'string', 'max:40'],
            'donor_address' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'frequency' => ['required', Rule::in(array_keys(Donation::FREQUENCIES))],
            'payment_method' => ['required', Rule::in(array_keys(Donation::METHODS))],
            'status' => ['required', Rule::in(array_keys(Donation::STATUSES))],
            'fund_type' => ['nullable', 'string', 'max:120'],
            'message' => ['nullable', 'string', 'max:2000'],
            'is_anonymous' => ['nullable', 'boolean'],
            'paid_at' => ['nullable', 'date'],
        ], [], [
            'donor_name' => 'nom du donateur',
            'donor_email' => 'e-mail',
            'amount' => 'montant',
            'status' => 'statut',
        ]);
    }
}
