<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $subscribers = Subscriber::query()
            ->when($request->filled('q'), fn ($query) => $query->where('email', 'like', '%'.$request->string('q').'%'))
            ->latest()
            ->paginate(40)
            ->withQueryString();

        return view('admin.subscribers.index', [
            'subscribers' => $subscribers,
            'total' => Subscriber::where('is_active', true)->count(),
            'thisMonth' => Subscriber::where('created_at', '>=', now()->startOfMonth())->count(),
        ]);
    }

    public function destroy(Subscriber $subscriber)
    {
        $email = $subscriber->email;
        $subscriber->delete();

        return back()->with('success', $email.' a été désinscrit de la newsletter.');
    }

    public function export(): StreamedResponse
    {
        $subscribers = Subscriber::where('is_active', true)->orderBy('email')->get();

        return response()->streamDownload(function () use ($subscribers) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['E-mail', 'Inscrit le'], ';');

            foreach ($subscribers as $subscriber) {
                fputcsv($handle, [$subscriber->email, $subscriber->created_at->format('d/m/Y')], ';');
            }

            fclose($handle);
        }, 'abonnes-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
