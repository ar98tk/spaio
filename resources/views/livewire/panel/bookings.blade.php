@php
    $money = static fn ($value) => $currency.' '.number_format((float) ($value ?? 0), 2);
    $statusTone = static fn (string $status): string => match ($status) {
        'pending' => 'warning',
        'confirmed' => 'primary',
        'completed' => 'success',
        'cancelled' => 'danger',
        default => 'light',
    };
    $chartData = collect($completedDailyChart)->values();
    $maxCompletedBookings = max(1, (int) ($chartData->max('completed_count') ?? 0));
    $maxCompletedAmount = max(1, (float) ($chartData->max('completed_amount') ?? 0));
    $totalCompletedThisMonth = (int) $chartData->sum('completed_count');
    $totalCompletedAmount = (float) $chartData->sum('completed_amount');
    $chartWidth = 720;
    $chartHeight = 160;
    $chartLeft = 34;
    $chartRight = 14;
    $chartTop = 14;
    $chartBottom = 30;
    $plotWidth = $chartWidth - $chartLeft - $chartRight;
    $plotHeight = $chartHeight - $chartTop - $chartBottom;
    $pointDenominator = max(1, $chartData->count() - 1);
    $completedPoints = $chartData->map(function (array $day, int $index) use ($chartLeft, $chartTop, $plotWidth, $plotHeight, $pointDenominator, $maxCompletedBookings): string {
        $x = $chartLeft + (($index / $pointDenominator) * $plotWidth);
        $y = $chartTop + ($plotHeight - (($day['completed_count'] / $maxCompletedBookings) * $plotHeight));

        return round($x, 2).','.round($y, 2);
    })->implode(' ');
    $amountPoints = $chartData->map(function (array $day, int $index) use ($chartLeft, $chartTop, $plotWidth, $plotHeight, $pointDenominator, $maxCompletedAmount): string {
        $x = $chartLeft + (($index / $pointDenominator) * $plotWidth);
        $y = $chartTop + ($plotHeight - (($day['completed_amount'] / $maxCompletedAmount) * $plotHeight));

        return round($x, 2).','.round($y, 2);
    })->implode(' ');
@endphp

<div>
    <div class="row g-4 g-xl-6 mb-5">
        <div class="col-xl-3">
            <div class="card card-flush h-100">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between gap-4 mb-4">
                        <div>
                            <span class="badge badge-light-primary fw-bold mb-2">Operations</span>
                            <h2 class="fs-4 fw-bold text-gray-900 mb-0">Booking pipeline</h2>
                        </div>
                        <span class="symbol symbol-45px">
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-calendar-tick fs-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </span>
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between rounded bg-light-primary px-4 py-3">
                        <div>
                            <div class="fs-8 text-gray-600 text-uppercase fw-bold">Total queue</div>
                            <div class="fs-2 fw-bold text-gray-900">{{ $summary['total'] ?? 0 }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fs-8 text-gray-600 text-uppercase fw-bold">Active</div>
                            <div class="fs-2 fw-bold text-primary">{{ ($summary['pending'] ?? 0) + ($summary['confirmed'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @foreach ([
            ['key' => 'pending', 'label' => 'Pending', 'tone' => 'warning', 'icon' => 'ki-time'],
            ['key' => 'confirmed', 'label' => 'Confirmed', 'tone' => 'primary', 'icon' => 'ki-calendar-tick'],
            ['key' => 'completed', 'label' => 'Completed', 'tone' => 'success', 'icon' => 'ki-check-circle'],
            ['key' => 'cancelled', 'label' => 'Cancelled', 'tone' => 'danger', 'icon' => 'ki-cross-circle'],
        ] as $stat)
            <div class="col-6 col-xl">
                <div class="card card-flush h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="fs-2 fw-bold text-gray-900">{{ $summary[$stat['key']] ?? 0 }}</div>
                            <span class="symbol symbol-35px">
                                <span class="symbol-label bg-light-{{ $stat['tone'] }}">
                                    <i class="ki-duotone {{ $stat['icon'] }} fs-3 text-{{ $stat['tone'] }}"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                </span>
                            </span>
                        </div>
                        <div class="fs-7 fw-semibold text-gray-500 text-uppercase">{{ $stat['label'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4 g-xl-6 mb-5 mb-xl-7">
        <div class="col-xl-3">
            <div class="card card-flush h-100">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="fs-6 fw-bold text-gray-900">Status mix</div>
                        <span class="badge badge-light">All sources</span>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $key)
                            @php
                                $total = max(1, $summary['total'] ?? 0);
                                $width = round((($summary[$key] ?? 0) / $total) * 100);
                            @endphp
                            <div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-gray-600 fs-8 text-capitalize">{{ $key }}</span>
                                    <span class="fw-bold text-gray-900 fs-8">{{ $summary[$key] ?? 0 }}</span>
                                </div>
                                <div class="h-6px bg-light rounded">
                                    <div class="bg-{{ $statusTone($key) }} rounded h-6px" style="width: {{ $width }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9">
            <div class="card card-flush h-100">
                <div class="card-header min-h-50px align-items-center">
                    <div>
                        <h3 class="card-title fw-bold text-gray-900 fs-4">Monthly bookings</h3>
                        <div class="text-gray-600 fs-7">Current month days: completed bookings and amount of money.</div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge badge-light-success">
                            <span class="bullet bullet-dot bg-success me-2"></span>{{ $totalCompletedThisMonth }} completed
                        </span>
                        <span class="badge badge-light-primary">
                            <span class="bullet bullet-dot bg-primary me-2"></span>{{ $money($totalCompletedAmount) }}
                        </span>
                    </div>
                </div>
                <div class="card-body pt-0 pb-4">
                    <div class="position-relative">
                        <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" class="w-100" style="height: 160px;" role="img" aria-label="Current month completed bookings and revenue chart">
                            <defs>
                                <linearGradient id="completedBookingsFill" x1="0" x2="0" y1="0" y2="1">
                                    <stop offset="0%" stop-color="var(--bs-success)" stop-opacity=".18"></stop>
                                    <stop offset="100%" stop-color="var(--bs-success)" stop-opacity="0"></stop>
                                </linearGradient>
                            </defs>

                            @for ($line = 0; $line <= 3; $line++)
                                @php
                                    $y = $chartTop + (($plotHeight / 3) * $line);
                                @endphp
                                <line x1="{{ $chartLeft }}" y1="{{ $y }}" x2="{{ $chartWidth - $chartRight }}" y2="{{ $y }}" stroke="var(--bs-gray-200)" stroke-width="1"></line>
                            @endfor

                            @if ($chartData->isNotEmpty())
                                <polyline
                                    points="{{ $chartLeft }},{{ $chartTop + $plotHeight }} {{ $completedPoints }} {{ $chartWidth - $chartRight }},{{ $chartTop + $plotHeight }}"
                                    fill="url(#completedBookingsFill)"
                                    stroke="none"
                                ></polyline>
                                <polyline points="{{ $completedPoints }}" fill="none" stroke="var(--bs-success)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></polyline>
                                <polyline points="{{ $amountPoints }}" fill="none" stroke="var(--bs-primary)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></polyline>

                                @foreach ($chartData as $index => $day)
                                    @php
                                        $x = $chartLeft + (($index / $pointDenominator) * $plotWidth);
                                        $showLabel = $index === 0 || $index === $chartData->count() - 1 || ((int) $day['label']) % 5 === 0;
                                    @endphp
                                    @if ($showLabel)
                                        <text x="{{ $x }}" y="{{ $chartHeight - 8 }}" text-anchor="middle" fill="var(--bs-gray-500)" font-size="10" font-weight="600">{{ $day['label'] }}</text>
                                    @endif
                                @endforeach
                            @endif
                        </svg>
                    </div>
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-2">
                        <div class="d-flex align-items-center gap-2 text-gray-600 fs-8">
                            <span class="bullet bullet-dot bg-success"></span>
                            <span>Total completed bookings, max day {{ $maxCompletedBookings }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-gray-600 fs-8">
                            <span class="bullet bullet-dot bg-primary"></span>
                            <span>Amount of money, max day {{ $money($maxCompletedAmount) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-flush">
        <div class="card-header">
            <h3 class="card-title fw-bold text-gray-900">Incoming bookings</h3>
        </div>
        <div class="card-body pt-0">
            <div class="d-flex flex-column gap-3">
                @forelse ($bookings as $booking)
                    <div class="border border-gray-200 rounded px-4 py-3 bg-hover-light" wire:key="booking-{{ $booking->id }}">
                        <div class="row g-3 align-items-center">
                            <div class="col-xl-5">
                                <div class="d-flex align-items-start gap-3">
                                    <span class="symbol symbol-42px">
                                        <span class="symbol-label bg-light-{{ $statusTone($booking->status) }}">
                                            <i class="ki-duotone ki-calendar-tick fs-3 text-{{ $statusTone($booking->status) }}"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        </span>
                                    </span>
                                    <div class="min-w-0">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                            <span class="fw-bold text-gray-900 fs-6">{{ $booking->item_name }}</span>
                                            <span class="badge badge-light-{{ $statusTone($booking->status) }}">{{ $booking->status }}</span>
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center gap-2 fs-7 text-gray-600">
                                            <span>{{ $money($booking->item_price) }}</span>
                                            <span class="bullet bullet-dot bg-gray-400"></span>
                                            <span>{{ $booking->item_type }}</span>
                                            <span class="bullet bullet-dot bg-gray-400"></span>
                                            <span>{{ $booking->source }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if ($booking->notes)
                                    <div class="mt-2 ps-14 text-gray-600 fs-8 text-truncate">
                                        {{ $booking->notes }}
                                    </div>
                                @endif
                            </div>

                            <div class="col-xl-3">
                                <div class="fw-bold text-gray-900 mb-1">{{ $booking->customer_name }}</div>
                                <a href="tel:{{ $booking->customer_phone }}" class="d-flex align-items-center gap-2 text-gray-600 fs-7 mb-1">
                                    <i class="ki-duotone ki-phone fs-4"><span class="path1"></span><span class="path2"></span></i>
                                    {{ $booking->customer_phone }}
                                </a>
                                @if ($booking->customer_email)
                                    <a href="mailto:{{ $booking->customer_email }}" class="d-flex align-items-center gap-2 text-gray-600 fs-7">
                                        <i class="ki-duotone ki-sms fs-4"><span class="path1"></span><span class="path2"></span></i>
                                        {{ $booking->customer_email }}
                                    </a>
                                @endif
                            </div>

                            <div class="col-xl-2">
                                <div class="d-flex align-items-center gap-2 text-gray-600 fs-7">
                                    <i class="ki-duotone ki-calendar fs-4"><span class="path1"></span><span class="path2"></span></i>
                                    <span>
                                        {{ $booking->preferred_date?->format('M j, Y') ?? 'Flexible date' }}
                                        @if ($booking->preferred_time)
                                            <br>{{ $booking->preferred_time }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="col-xl-2">
                                @if ($canUpdate)
                                    <div class="d-flex flex-wrap gap-1 justify-content-xl-end">
                                        @foreach ($statuses as $status)
                                            <button
                                                type="button"
                                                wire:click="updateStatus({{ $booking->id }}, '{{ $status }}')"
                                                class="btn btn-sm py-1 px-2 {{ $booking->status === $status ? 'btn-primary' : 'btn-light' }} text-capitalize fs-8"
                                                wire:loading.attr="disabled"
                                            >
                                                {{ $status }}
                                            </button>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="badge badge-light">View only</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-15">
                        <div class="symbol symbol-80px mb-6">
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-calendar-tick fs-3x text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </span>
                        </div>
                        <h3 class="fw-bold text-gray-900">No bookings yet</h3>
                        <p class="text-gray-600 mb-0">Bookings will appear here after customers submit the website form or reception creates an entry.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
