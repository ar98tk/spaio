<?php

namespace App\Livewire\Panel;

use App\Models\Booking;
use App\Models\GalleryImage;
use App\Models\Package;
use App\Models\Service;
use App\Models\StaffMember;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Overview extends Component
{
    public function render(): View
    {
        $tenant = auth()->user()?->tenant;

        abort_unless($tenant, 403);

        $subscription = $tenant->subscription()->with('plan')->first();
        $bookingCounts = Booking::query()
            ->forTenant($tenant->id)
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');
        $serviceCounts = Service::query()
            ->forTenant($tenant->id)
            ->selectRaw('is_active, count(*) as aggregate')
            ->groupBy('is_active')
            ->pluck('aggregate', 'is_active');
        $bookingRevenue = Booking::query()
            ->forTenant($tenant->id)
            ->where('status', 'completed')
            ->sum('item_price');

        return view('livewire.panel.overview', [
            'tenant' => $tenant,
            'subscription' => $subscription,
            'counts' => [
                'services' => $serviceCounts->sum(),
                'active_services' => (int) ($serviceCounts[1] ?? 0),
                'hidden_services' => (int) ($serviceCounts[0] ?? 0),
                'packages' => Package::query()->forTenant($tenant->id)->count(),
                'staff' => StaffMember::query()->forTenant($tenant->id)->count(),
                'gallery' => GalleryImage::query()->forTenant($tenant->id)->count(),
                'bookings' => $bookingCounts->sum(),
                'pending_bookings' => (int) ($bookingCounts['pending'] ?? 0),
                'confirmed_bookings' => (int) ($bookingCounts['confirmed'] ?? 0),
                'completed_bookings' => (int) ($bookingCounts['completed'] ?? 0),
                'cancelled_bookings' => (int) ($bookingCounts['cancelled'] ?? 0),
                'completed_revenue' => (float) $bookingRevenue,
            ],
            'setupSteps' => [
                ['label' => 'Business profile', 'complete' => filled($tenant->business_name) && filled($tenant->phone)],
                ['label' => 'Website hero', 'complete' => filled($tenant->hero_title) && filled($tenant->hero_image_path)],
                ['label' => 'Services menu', 'complete' => Service::query()->forTenant($tenant->id)->where('is_active', true)->exists()],
                ['label' => 'Staff profiles', 'complete' => StaffMember::query()->forTenant($tenant->id)->exists()],
                ['label' => 'Booking intake', 'complete' => Booking::query()->forTenant($tenant->id)->exists()],
            ],
        ])->layout('components.layouts.panel', [
            'title' => 'Dashboard overview',
        ]);
    }
}
