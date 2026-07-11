<?php

namespace App\Livewire\Panel;

use App\Models\Booking;
use App\Support\TenantPermissions;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Bookings extends Component
{
    public function render(): View
    {
        abort_unless(TenantPermissions::group(auth()->user(), 'bookings'), 403);

        $bookings = $this->bookings();

        return view('livewire.panel.bookings', [
            'bookings' => $bookings,
            'summary' => [
                'total' => $bookings->count(),
                'pending' => $bookings->where('status', 'pending')->count(),
                'confirmed' => $bookings->where('status', 'confirmed')->count(),
                'completed' => $bookings->where('status', 'completed')->count(),
                'cancelled' => $bookings->where('status', 'cancelled')->count(),
            ],
            'statuses' => Booking::STATUSES,
            'currency' => $this->tenantCurrency(),
            'canUpdate' => TenantPermissions::can(auth()->user(), 'bookings.update'),
            'completedDailyChart' => $this->completedDailyChart(),
        ])->layout('components.layouts.panel', [
            'title' => 'Bookings',
        ]);
    }

    public function updateStatus(int $bookingId, string $status): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'bookings.update'), 403);

        validator(['status' => $status], [
            'status' => ['required', Rule::in(Booking::STATUSES)],
        ])->validate();

        $booking = Booking::query()
            ->forTenant($this->tenantId())
            ->findOrFail($bookingId);

        $booking->update(['status' => $status]);

        session()->flash('success', 'Booking status updated.');
    }

    /**
     * @return Collection<int, Booking>
     */
    private function bookings(): Collection
    {
        return Booking::query()
            ->forTenant($this->tenantId())
            ->latest()
            ->get();
    }

    /**
     * @return array<int, array{label: string, completed_count: int, completed_amount: float}>
     */
    private function completedDailyChart(): array
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();
        $driver = Booking::query()->getConnection()->getDriverName();
        $dayExpression = $driver === 'sqlite'
            ? "strftime('%Y-%m-%d', created_at)"
            : "DATE(created_at)";
        $rows = Booking::query()
            ->forTenant($this->tenantId())
            ->where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw($dayExpression.' as booked_day, count(*) as completed_count, coalesce(sum(item_price), 0) as completed_amount')
            ->groupBy('booked_day')
            ->get()
            ->keyBy('booked_day');

        return collect(range(1, $end->day))
            ->map(function (int $offset) use ($start, $rows): array {
                $day = $start->copy()->addDays($offset - 1);
                $key = $day->format('Y-m-d');
                $row = $rows[$key] ?? null;

                return [
                    'label' => $day->format('j'),
                    'completed_count' => (int) ($row?->completed_count ?? 0),
                    'completed_amount' => (float) ($row?->completed_amount ?? 0),
                ];
            })
            ->all();
    }

    private function tenantCurrency(): string
    {
        return auth()->user()?->tenant?->currency_code ?: 'USD';
    }

    private function tenantId(): int
    {
        $tenantId = auth()->user()?->tenant_id;

        abort_unless($tenantId, 403);

        return (int) $tenantId;
    }
}
