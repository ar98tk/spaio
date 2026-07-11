<?php

namespace App\Livewire\Panel;

use App\Models\Booking;
use App\Models\TenantWebsiteActionEvent;
use App\Support\TenantPermissions;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Statistics extends Component
{
    public function render(): View
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'analytics.view'), 403);

        $tenant = auth()->user()?->tenant;
        abort_unless($tenant, 403);

        $start = now()->startOfMonth();
        $end = now()->endOfMonth();
        $previousStart = now()->subMonthNoOverflow()->startOfMonth();
        $previousEnd = now()->subMonthNoOverflow()->endOfMonth();

        $monthBookings = Booking::query()
            ->forTenant($tenant->id)
            ->whereBetween('created_at', [$start, $end])
            ->get();
        $completedMonth = $monthBookings->where('status', 'completed');
        $allBookings = Booking::query()->forTenant($tenant->id)->get();
        $monthActions = TenantWebsiteActionEvent::query()
            ->forTenant($tenant->id)
            ->whereBetween('created_at', [$start, $end])
            ->get();
        $previousActions = TenantWebsiteActionEvent::query()
            ->forTenant($tenant->id)
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->get();

        return view('livewire.panel.statistics', [
            'tenant' => $tenant,
            'currency' => $tenant->currency_code ?: 'USD',
            'summary' => $this->summary($monthBookings, $completedMonth, $allBookings),
            'dailyChart' => $this->dailyChart($tenant->id),
            'statusBreakdown' => $this->statusBreakdown($monthBookings),
            'sourceBreakdown' => $this->sourceBreakdown($monthBookings),
            'topServices' => $this->topItems($monthBookings, 'service'),
            'topPackages' => $this->topItems($monthBookings, 'package'),
            'actionSummary' => $this->actionSummary($monthActions, $previousActions),
            'actionDailyChart' => $this->actionDailyChart($tenant->id),
        ])->layout('components.layouts.panel', [
            'title' => 'Statistics',
        ]);
    }

    private function summary(Collection $monthBookings, Collection $completedMonth, Collection $allBookings): array
    {
        $monthRevenue = (float) $completedMonth->sum('item_price');
        $uniqueCustomers = $allBookings
            ->map(fn (Booking $booking): string => str($booking->customer_email ?: $booking->customer_phone)->lower()->trim()->toString())
            ->filter()
            ->unique()
            ->count();
        $returningCustomers = $allBookings
            ->map(fn (Booking $booking): string => str($booking->customer_email ?: $booking->customer_phone)->lower()->trim()->toString())
            ->filter()
            ->countBy()
            ->filter(fn (int $count): bool => $count > 1)
            ->count();

        return [
            'month_bookings' => $monthBookings->count(),
            'completed_bookings' => $completedMonth->count(),
            'pending_bookings' => $monthBookings->where('status', 'pending')->count(),
            'confirmed_bookings' => $monthBookings->where('status', 'confirmed')->count(),
            'cancelled_bookings' => $monthBookings->where('status', 'cancelled')->count(),
            'month_revenue' => $monthRevenue,
            'average_ticket' => $completedMonth->count() ? $monthRevenue / $completedMonth->count() : 0,
            'unique_customers' => $uniqueCustomers,
            'returning_customers' => $returningCustomers,
        ];
    }

    /**
     * @return array<int, array{label: string, completed_count: int, completed_amount: float, total_count: int}>
     */
    private function dailyChart(int $tenantId): array
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();
        $driver = Booking::query()->getConnection()->getDriverName();
        $dayExpression = $driver === 'sqlite'
            ? "strftime('%Y-%m-%d', created_at)"
            : 'DATE(created_at)';

        $completedRows = Booking::query()
            ->forTenant($tenantId)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw($dayExpression.' as booked_day, count(*) as completed_count, coalesce(sum(item_price), 0) as completed_amount')
            ->groupBy('booked_day')
            ->get()
            ->keyBy('booked_day');
        $totalRows = Booking::query()
            ->forTenant($tenantId)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw($dayExpression.' as booked_day, count(*) as total_count')
            ->groupBy('booked_day')
            ->get()
            ->keyBy('booked_day');

        return collect(range(1, $end->day))
            ->map(function (int $offset) use ($start, $completedRows, $totalRows): array {
                $day = $start->copy()->addDays($offset - 1);
                $key = $day->format('Y-m-d');
                $completed = $completedRows[$key] ?? null;
                $total = $totalRows[$key] ?? null;

                return [
                    'label' => $day->format('j'),
                    'completed_count' => (int) ($completed?->completed_count ?? 0),
                    'completed_amount' => (float) ($completed?->completed_amount ?? 0),
                    'total_count' => (int) ($total?->total_count ?? 0),
                ];
            })
            ->all();
    }

    private function statusBreakdown(Collection $bookings): array
    {
        return collect(Booking::STATUSES)
            ->map(fn (string $status): array => [
                'label' => str($status)->headline()->toString(),
                'value' => $bookings->where('status', $status)->count(),
            ])
            ->all();
    }

    private function sourceBreakdown(Collection $bookings): array
    {
        return collect(['website', 'reception'])
            ->map(fn (string $source): array => [
                'label' => str($source)->headline()->toString(),
                'value' => $bookings->where('source', $source)->count(),
                'amount' => (float) $bookings->where('source', $source)->where('status', 'completed')->sum('item_price'),
            ])
            ->all();
    }

    private function topItems(Collection $bookings, string $type): array
    {
        return $bookings
            ->where('item_type', $type)
            ->groupBy('item_name')
            ->map(fn (Collection $items, string $name): array => [
                'name' => $name,
                'bookings' => $items->count(),
                'revenue' => (float) $items->where('status', 'completed')->sum('item_price'),
            ])
            ->sortByDesc('bookings')
            ->take(5)
            ->values()
            ->all();
    }

    private function actionSummary(Collection $monthActions, Collection $previousActions): array
    {
        return collect(TenantWebsiteActionEvent::TYPES)
            ->map(function (string $type) use ($monthActions, $previousActions): array {
                $current = $monthActions->where('action_type', $type)->count();
                $previous = $previousActions->where('action_type', $type)->count();

                return [
                    'type' => $type,
                    'label' => str($type)->headline()->toString(),
                    'current' => $current,
                    'today' => $monthActions
                        ->where('action_type', $type)
                        ->filter(fn (TenantWebsiteActionEvent $event): bool => $event->created_at?->isToday() ?? false)
                        ->count(),
                    'previous' => $previous,
                    'change' => $previous > 0 ? round((($current - $previous) / $previous) * 100) : ($current > 0 ? 100 : 0),
                ];
            })
            ->all();
    }

    /**
     * @return array<int, array{label: string, whatsapp: int, call: int, location: int}>
     */
    private function actionDailyChart(int $tenantId): array
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();
        $driver = TenantWebsiteActionEvent::query()->getConnection()->getDriverName();
        $dayExpression = $driver === 'sqlite'
            ? "strftime('%Y-%m-%d', created_at)"
            : 'DATE(created_at)';
        $rows = TenantWebsiteActionEvent::query()
            ->forTenant($tenantId)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw($dayExpression.' as action_day, action_type, count(*) as aggregate')
            ->groupBy('action_day', 'action_type')
            ->get()
            ->groupBy('action_day');

        return collect(range(1, $end->day))
            ->map(function (int $offset) use ($start, $rows): array {
                $day = $start->copy()->addDays($offset - 1);
                $items = $rows[$day->format('Y-m-d')] ?? collect();

                return [
                    'label' => $day->format('j'),
                    'whatsapp' => (int) ($items->firstWhere('action_type', 'whatsapp')?->aggregate ?? 0),
                    'call' => (int) ($items->firstWhere('action_type', 'call')?->aggregate ?? 0),
                    'location' => (int) ($items->firstWhere('action_type', 'location')?->aggregate ?? 0),
                ];
            })
            ->all();
    }
}
