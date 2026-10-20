<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cause;
use App\Models\Comment;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\Event;
use App\Models\Post;
use App\Models\Subscriber;
use App\Models\Volunteer;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'kpis' => $this->kpis(),
            'monthly' => $this->monthlyDonations(),
            'topCauses' => $this->topCauses(),
            'statusBreakdown' => $this->statusBreakdown(),
            'recentDonations' => Donation::with('cause')->latest()->take(8)->get(),
            'pendingDonations' => Donation::with('cause')->where('status', 'pending')->latest()->take(5)->get(),
            'todo' => [
                'donations' => Donation::where('status', 'pending')->count(),
                'comments' => Comment::where('status', 'pending')->count(),
                'volunteers' => Volunteer::where('status', 'pending')->count(),
                'messages' => ContactMessage::unread()->count(),
            ],
            'upcomingEvents' => Event::published()->upcoming()->withCount('registrations')->orderBy('starts_at')->take(5)->get(),
        ]);
    }

    /**
     * Headline numbers, each with its 30-day variation.
     */
    private function kpis(): array
    {
        $collected = (float) Donation::completed()->sum('amount');

        $last30 = (float) Donation::completed()
            ->where('paid_at', '>=', now()->subDays(30))
            ->sum('amount');

        $previous30 = (float) Donation::completed()
            ->whereBetween('paid_at', [now()->subDays(60), now()->subDays(30)])
            ->sum('amount');

        return [
            'collected' => $collected,
            'collected_trend' => $previous30 > 0 ? (int) round((($last30 - $previous30) / $previous30) * 100) : null,
            'last30' => $last30,
            'donors' => Donation::completed()->distinct('donor_email')->count('donor_email'),
            'average' => Donation::completed()->avg('amount') ?? 0,
            'causes' => Cause::published()->count(),
            'posts' => Post::published()->count(),
            'subscribers' => Subscriber::where('is_active', true)->count(),
            'volunteers' => Volunteer::where('status', 'approved')->count(),
        ];
    }

    /**
     * Twelve months of validated donations, oldest first.
     */
    private function monthlyDonations(): array
    {
        $start = now()->copy()->startOfMonth()->subMonths(11);

        $totals = Donation::completed()
            ->where('paid_at', '>=', $start)
            ->get(['paid_at', 'amount'])
            ->groupBy(fn (Donation $donation) => $donation->paid_at->format('Y-m'))
            ->map(fn ($group) => (float) $group->sum('amount'));

        return collect(range(0, 11))
            ->map(function (int $offset) use ($start, $totals) {
                $month = $start->copy()->addMonths($offset);

                return [
                    'label' => Carbon::parse($month)->translatedFormat('M'),
                    'caption' => Carbon::parse($month)->translatedFormat('F Y'),
                    'value' => $totals[$month->format('Y-m')] ?? 0,
                ];
            })
            ->all();
    }

    private function topCauses(): array
    {
        return Cause::query()
            ->orderByDesc('raised_amount')
            ->take(6)
            ->get(['id', 'title', 'raised_amount', 'goal_amount'])
            ->map(fn (Cause $cause) => [
                'label' => $cause->title,
                'value' => (float) $cause->raised_amount,
                'href' => route('admin.causes.edit', $cause),
            ])
            ->all();
    }

    private function statusBreakdown(): array
    {
        $rows = Donation::query()
            ->selectRaw('status, count(*) as total, sum(amount) as amount')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return collect(Donation::STATUSES)
            ->map(fn (string $label, string $status) => [
                'status' => $status,
                'label' => $label,
                'total' => (int) ($rows[$status]->total ?? 0),
                'amount' => (float) ($rows[$status]->amount ?? 0),
            ])
            ->values()
            ->all();
    }
}
