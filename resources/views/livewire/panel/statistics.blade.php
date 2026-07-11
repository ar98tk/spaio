@php
    $money = static fn ($value) => $currency.' '.number_format((float) ($value ?? 0), 2);
    $chartData = collect($dailyChart)->values();
    $actionChartData = collect($actionDailyChart)->values();
    $chartWidth = 720;
    $chartHeight = 180;
    $chartLeft = 34;
    $chartRight = 14;
    $chartTop = 16;
    $chartBottom = 32;
    $plotWidth = $chartWidth - $chartLeft - $chartRight;
    $plotHeight = $chartHeight - $chartTop - $chartBottom;
    $pointDenominator = max(1, $chartData->count() - 1);
    $maxCompleted = max(1, (int) ($chartData->max('completed_count') ?? 0));
    $maxRevenue = max(1, (float) ($chartData->max('completed_amount') ?? 0));
    $maxActions = max(1, (int) collect(['whatsapp', 'call', 'location'])->map(fn ($key) => $actionChartData->max($key) ?? 0)->max());
    $points = static function ($data, string $key, int|float $max) use ($chartLeft, $chartTop, $plotWidth, $plotHeight, $pointDenominator): string {
        return collect($data)->map(function (array $day, int $index) use ($key, $max, $chartLeft, $chartTop, $plotWidth, $plotHeight, $pointDenominator): string {
            $x = $chartLeft + (($index / $pointDenominator) * $plotWidth);
            $y = $chartTop + ($plotHeight - (((float) ($day[$key] ?? 0) / $max) * $plotHeight));

            return round($x, 2).','.round($y, 2);
        })->implode(' ');
    };
    $completedPoints = $points($chartData, 'completed_count', $maxCompleted);
    $revenuePoints = $points($chartData, 'completed_amount', $maxRevenue);
    $whatsappPoints = $points($actionChartData, 'whatsapp', $maxActions);
    $callPoints = $points($actionChartData, 'call', $maxActions);
    $locationPoints = $points($actionChartData, 'location', $maxActions);
@endphp

<div>
    <div class="row g-4 g-xl-6 mb-5">
        @foreach ([
            ['label' => 'Bookings this month', 'value' => $summary['month_bookings'], 'sub' => $summary['pending_bookings'].' pending', 'tone' => 'primary', 'icon' => 'ki-calendar-tick'],
            ['label' => 'Completed revenue', 'value' => $money($summary['month_revenue']), 'sub' => $summary['completed_bookings'].' completed', 'tone' => 'success', 'icon' => 'ki-dollar'],
            ['label' => 'Average ticket', 'value' => $money($summary['average_ticket']), 'sub' => 'Per completed booking', 'tone' => 'info', 'icon' => 'ki-chart-line-up'],
            ['label' => 'Customers', 'value' => $summary['unique_customers'], 'sub' => $summary['returning_customers'].' returning', 'tone' => 'warning', 'icon' => 'ki-profile-user'],
        ] as $metric)
            <div class="col-md-6 col-xl-3">
                <div class="card card-flush h-100">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <span class="fw-semibold text-gray-600">{{ $metric['label'] }}</span>
                            <span class="symbol symbol-40px">
                                <span class="symbol-label bg-light-{{ $metric['tone'] }}">
                                    <i class="ki-duotone {{ $metric['icon'] }} fs-2 text-{{ $metric['tone'] }}"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                </span>
                            </span>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900">{{ $metric['value'] }}</div>
                        <div class="text-gray-600 fw-semibold mt-2">{{ $metric['sub'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4 g-xl-6 mb-5">
        <div class="col-xl-8">
            <div class="card card-flush h-100">
                <div class="card-header align-items-center">
                    <div>
                        <h3 class="card-title fw-bold text-gray-900">Monthly performance</h3>
                        <div class="text-gray-600 fs-7">Completed bookings and money by current-month day.</div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge badge-light-success"><span class="bullet bullet-dot bg-success me-2"></span>Completed</span>
                        <span class="badge badge-light-primary"><span class="bullet bullet-dot bg-primary me-2"></span>Revenue</span>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" class="w-100" style="height: 180px;" role="img" aria-label="Completed bookings and revenue chart">
                        @for ($line = 0; $line <= 4; $line++)
                            @php $y = $chartTop + (($plotHeight / 4) * $line); @endphp
                            <line x1="{{ $chartLeft }}" y1="{{ $y }}" x2="{{ $chartWidth - $chartRight }}" y2="{{ $y }}" stroke="var(--bs-gray-200)" stroke-width="1"></line>
                        @endfor
                        <polyline points="{{ $completedPoints }}" fill="none" stroke="var(--bs-success)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></polyline>
                        <polyline points="{{ $revenuePoints }}" fill="none" stroke="var(--bs-primary)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></polyline>
                        @foreach ($chartData as $index => $day)
                            @php
                                $x = $chartLeft + (($index / $pointDenominator) * $plotWidth);
                                $showLabel = $index === 0 || $index === $chartData->count() - 1 || ((int) $day['label']) % 5 === 0;
                            @endphp
                            @if ($showLabel)
                                <text x="{{ $x }}" y="{{ $chartHeight - 8 }}" text-anchor="middle" fill="var(--bs-gray-500)" font-size="10" font-weight="600">{{ $day['label'] }}</text>
                            @endif
                        @endforeach
                    </svg>
                    <div class="d-flex flex-wrap justify-content-between gap-3 mt-3">
                        <div class="text-gray-600 fs-7">Max completed day: <span class="fw-bold text-gray-900">{{ $maxCompleted }}</span></div>
                        <div class="text-gray-600 fs-7">Max revenue day: <span class="fw-bold text-gray-900">{{ $money($maxRevenue) }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card card-flush h-100">
                <div class="card-header">
                    <h3 class="card-title fw-bold text-gray-900">Booking mix</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-6">
                        <div class="fs-7 fw-bold text-gray-500 text-uppercase mb-3">Status</div>
                        @foreach ($statusBreakdown as $item)
                            @php $width = round(($item['value'] / max(1, $summary['month_bookings'])) * 100); @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between fs-7 mb-1">
                                    <span class="text-gray-700">{{ $item['label'] }}</span>
                                    <span class="fw-bold text-gray-900">{{ $item['value'] }}</span>
                                </div>
                                <div class="h-6px bg-light rounded"><div class="bg-primary rounded h-6px" style="width: {{ $width }}%"></div></div>
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <div class="fs-7 fw-bold text-gray-500 text-uppercase mb-3">Source</div>
                        @foreach ($sourceBreakdown as $item)
                            <div class="d-flex align-items-center justify-content-between border border-gray-200 rounded px-4 py-3 mb-3">
                                <div>
                                    <div class="fw-bold text-gray-900">{{ $item['label'] }}</div>
                                    <div class="text-gray-600 fs-8">{{ $money($item['amount']) }} completed revenue</div>
                                </div>
                                <span class="badge badge-light-primary">{{ $item['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 g-xl-6 mb-5">
        <div class="col-xl-4">
            <div class="card card-flush h-100">
                <div class="card-header">
                    <h3 class="card-title fw-bold text-gray-900">Website action report</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-3">
                        @foreach ($actionSummary as $action)
                            @php
                                $tone = match ($action['type']) {
                                    'whatsapp' => 'success',
                                    'call' => 'primary',
                                    'location' => 'warning',
                                    default => 'secondary',
                                };
                                $icon = match ($action['type']) {
                                    'whatsapp' => 'ki-whatsapp',
                                    'call' => 'ki-phone',
                                    'location' => 'ki-geolocation',
                                    default => 'ki-click',
                                };
                            @endphp
                            <div class="d-flex align-items-center justify-content-between border border-gray-200 rounded px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="symbol symbol-40px">
                                        <span class="symbol-label bg-light-{{ $tone }}">
                                            <i class="ki-duotone {{ $icon }} fs-2 text-{{ $tone }}"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                    </span>
                                    <div>
                                        <div class="fw-bold text-gray-900">{{ $action['label'] }}</div>
                                        <div class="text-gray-600 fs-8">{{ $action['today'] }} today, {{ $action['previous'] }} last month</div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fs-3 fw-bold text-gray-900">{{ $action['current'] }}</div>
                                    <span class="badge badge-light-{{ $action['change'] >= 0 ? 'success' : 'danger' }}">{{ $action['change'] }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card card-flush h-100">
                <div class="card-header align-items-center">
                    <div>
                        <h3 class="card-title fw-bold text-gray-900">Website button clicks</h3>
                        <div class="text-gray-600 fs-7">Daily WhatsApp, call, and location actions for the current month.</div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge badge-light-success"><span class="bullet bullet-dot bg-success me-2"></span>WhatsApp</span>
                        <span class="badge badge-light-primary"><span class="bullet bullet-dot bg-primary me-2"></span>Call</span>
                        <span class="badge badge-light-warning"><span class="bullet bullet-dot bg-warning me-2"></span>Location</span>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" class="w-100" style="height: 180px;" role="img" aria-label="Website action click chart">
                        @for ($line = 0; $line <= 4; $line++)
                            @php $y = $chartTop + (($plotHeight / 4) * $line); @endphp
                            <line x1="{{ $chartLeft }}" y1="{{ $y }}" x2="{{ $chartWidth - $chartRight }}" y2="{{ $y }}" stroke="var(--bs-gray-200)" stroke-width="1"></line>
                        @endfor
                        <polyline points="{{ $whatsappPoints }}" fill="none" stroke="var(--bs-success)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></polyline>
                        <polyline points="{{ $callPoints }}" fill="none" stroke="var(--bs-primary)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></polyline>
                        <polyline points="{{ $locationPoints }}" fill="none" stroke="var(--bs-warning)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></polyline>
                        @foreach ($actionChartData as $index => $day)
                            @php
                                $x = $chartLeft + (($index / $pointDenominator) * $plotWidth);
                                $showLabel = $index === 0 || $index === $actionChartData->count() - 1 || ((int) $day['label']) % 5 === 0;
                            @endphp
                            @if ($showLabel)
                                <text x="{{ $x }}" y="{{ $chartHeight - 8 }}" text-anchor="middle" fill="var(--bs-gray-500)" font-size="10" font-weight="600">{{ $day['label'] }}</text>
                            @endif
                        @endforeach
                    </svg>
                    <div class="text-gray-600 fs-7 mt-3">Max action count in one day: <span class="fw-bold text-gray-900">{{ $maxActions }}</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 g-xl-6">
        @foreach ([['title' => 'Top services', 'items' => $topServices], ['title' => 'Top packages', 'items' => $topPackages]] as $table)
            <div class="col-xl-6">
                <div class="card card-flush h-100">
                    <div class="card-header">
                        <h3 class="card-title fw-bold text-gray-900">{{ $table['title'] }}</h3>
                    </div>
                    <div class="card-body pt-0">
                        @forelse ($table['items'] as $item)
                            <div class="d-flex align-items-center justify-content-between border-bottom border-gray-200 py-4">
                                <div>
                                    <div class="fw-bold text-gray-900">{{ $item['name'] }}</div>
                                    <div class="text-gray-600 fs-8">{{ $item['bookings'] }} bookings</div>
                                </div>
                                <div class="fw-bold text-gray-900">{{ $money($item['revenue']) }}</div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <div class="symbol symbol-60px mb-4">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-chart-line-up fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                </div>
                                <div class="fw-bold text-gray-900">No data yet</div>
                                <div class="text-gray-600 fs-7">Bookings will populate this report.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
