@php
    $money = static fn ($value) => $currency.' '.number_format((float) ($value ?? 0), 2);
    $today = now()->toDateString();
    $todayCount = $recentBookings->filter(fn ($booking) => $booking->preferred_date?->format('Y-m-d') === $today)->count();
    $confirmedCount = $recentBookings->where('status', 'confirmed')->count();
    $recentValue = $recentBookings->sum(fn ($booking) => (float) $booking->item_price);
    $selectedLabel = 'Custom walk-in item';
    $selectedPrice = $item_price;

    if ($item_type === 'service' && $item_id) {
        $selectedService = $services->firstWhere('id', $item_id);
        $selectedLabel = $selectedService?->name ?? 'Selected service';
        $selectedPrice = $selectedService?->price;
    }

    if ($item_type === 'package' && $item_id) {
        $selectedPackage = $packages->firstWhere('id', $item_id);
        $selectedLabel = $selectedPackage?->name ?? 'Selected package';
        $selectedPrice = $selectedPackage?->discounted_price;
    }
@endphp

<div>
    <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        <div class="col-xl-6">
            <div class="card card-flush h-xl-100">
                <div class="card-body p-9">
                    <span class="badge badge-light-primary fw-bold mb-4">Reception portal</span>
                    <h2 class="fs-2 fw-bold text-gray-900 mb-3">{{ $isReceptionist ? 'Reception tablet portal' : 'Front-desk booking desk' }}</h2>
                    <p class="text-gray-600 mb-0 mw-650px">
                        Add walk-in, phone, and in-person requests quickly. Reception bookings stay connected to the main bookings pipeline.
                    </p>
                </div>
            </div>
        </div>

        @foreach ([
            ['label' => 'Today', 'value' => $todayCount, 'tone' => 'primary', 'icon' => 'ki-calendar-tick'],
            ['label' => 'Confirmed', 'value' => $confirmedCount, 'tone' => 'success', 'icon' => 'ki-check-circle'],
            ['label' => 'Recent value', 'value' => $money($recentValue), 'tone' => 'warning', 'icon' => 'ki-dollar'],
        ] as $stat)
            <div class="col-md-4 col-xl-2">
                <div class="card card-flush h-100">
                    <div class="card-body">
                        <span class="symbol symbol-45px mb-5">
                            <span class="symbol-label bg-light-{{ $stat['tone'] }}">
                                <i class="ki-duotone {{ $stat['icon'] }} fs-2x text-{{ $stat['tone'] }}"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </span>
                        </span>
                        <div class="fs-2 fw-bold text-gray-900">{{ $stat['value'] }}</div>
                        <div class="fs-7 fw-semibold text-gray-500 text-uppercase">{{ $stat['label'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-5 g-xl-8 {{ $isReceptionist ? 'justify-content-center' : '' }}">
        <div class="{{ $isReceptionist ? 'col-xxl-9 col-xl-10' : 'col-xl-6' }}">
            <div class="card card-flush">
                <div class="card-header">
                    <h3 class="card-title fw-bold text-gray-900">Create booking</h3>
                </div>
                <form wire:submit="save">
                    <div class="card-body pt-0">
                        <div class="row g-5">
                            <div class="col-12">
                                <label class="form-label required">Service, package, or custom item</label>
                                <select class="form-select" wire:model.live="selected_item">
                                    @foreach ($services as $service)
                                        <option value="service:{{ $service->id }}">{{ $service->name }} - {{ $money($service->price) }}</option>
                                    @endforeach
                                    @foreach ($packages as $package)
                                        <option value="package:{{ $package->id }}">{{ $package->name }} - {{ $money($package->discounted_price) }}</option>
                                    @endforeach
                                    <option value="custom">Custom walk-in item</option>
                                </select>
                                @error('item_type') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                @error('item_id') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">
                                    <i class="ki-duotone ki-information-5 fs-2tx text-primary me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    <div class="d-flex flex-stack flex-grow-1 flex-wrap gap-3">
                                        <div>
                                            <div class="fw-bold text-gray-900">{{ $item_type === 'custom' ? ($item_name ?: 'Custom walk-in item') : $selectedLabel }}</div>
                                            <span class="badge badge-light">{{ $item_type }}</span>
                                        </div>
                                        <div class="fs-4 fw-bold text-primary">{{ $money($selectedPrice) }}</div>
                                    </div>
                                </div>
                            </div>

                            @if ($item_type === 'custom')
                                <div class="col-md-6">
                                    <label class="form-label required">Custom item name</label>
                                    <input type="text" class="form-control" wire:model="item_name" placeholder="Walk-in consultation">
                                    @error('item_name') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Custom price</label>
                                    <input type="number" min="0" step="0.01" class="form-control" wire:model="item_price">
                                    @error('item_price') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <div class="col-md-6">
                                <label class="form-label required">Customer name</label>
                                <input type="text" class="form-control" wire:model="customer_name" placeholder="Customer full name">
                                @error('customer_name') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Customer phone</label>
                                <input type="text" class="form-control" wire:model="customer_phone" placeholder="+971 50 000 0000">
                                @error('customer_phone') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Customer email</label>
                                <input type="email" class="form-control" wire:model="customer_email" placeholder="Optional email address">
                                @error('customer_email') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" wire:model="preferred_date">
                                @error('preferred_date') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Time</label>
                                <input type="time" class="form-control" wire:model="preferred_time">
                                @error('preferred_time') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Status</label>
                                <select class="form-select" wire:model="status">
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                @error('status') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Reception notes</label>
                                <textarea rows="4" class="form-control" wire:model="notes" placeholder="Guest count, preferred room, therapist request, or payment note."></textarea>
                                @error('notes') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary {{ $isReceptionist ? 'w-100 py-4 fs-4' : '' }}" wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">Create booking</span>
                            <span wire:loading wire:target="save">Creating...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @unless ($isReceptionist)
            <div class="col-xl-6">
                <div class="card card-flush">
                    <div class="card-header">
                        <h3 class="card-title fw-bold text-gray-900">Recent reception bookings</h3>
                    </div>
                    <div class="card-body pt-0">
                        <div class="d-flex flex-column gap-4">
                            @forelse ($recentBookings as $booking)
                                <div class="border border-dashed border-gray-300 rounded p-5">
                                    <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
                                        <div>
                                            <div class="fw-bold text-gray-900 fs-5">{{ $booking->item_name }}</div>
                                            <div class="text-primary fw-bold">{{ $money($booking->item_price) }}</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <span class="badge {{ $booking->status === 'completed' ? 'badge-light-success' : ($booking->status === 'pending' ? 'badge-light-warning' : 'badge-light-primary') }}">{{ $booking->status }}</span>
                                            <span class="badge badge-light">{{ $booking->item_type }}</span>
                                        </div>
                                    </div>
                                    <div class="row g-3 text-gray-600">
                                        <div class="col-md-7">
                                            <div class="fw-semibold text-gray-900">{{ $booking->customer_name }}</div>
                                            <div>{{ $booking->customer_phone }}</div>
                                        </div>
                                        <div class="col-md-5 text-md-end">
                                            <div>{{ $booking->preferred_date?->format('M j, Y') ?? 'Today' }}</div>
                                            @if ($booking->preferred_time)
                                                <div>{{ $booking->preferred_time }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-15">
                                    <div class="symbol symbol-80px mb-6">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="ki-duotone ki-calendar-add fs-3x text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        </span>
                                    </div>
                                    <h3 class="fw-bold text-gray-900">No reception bookings yet</h3>
                                    <p class="text-gray-600 mb-0">Bookings created here will appear in this list and in the Bookings page.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endunless
    </div>
</div>
