@php
    $activeSteps = collect($setupSteps)->where('complete', true)->count();
    $progress = count($setupSteps) ? round(($activeSteps / count($setupSteps)) * 100) : 0;
    $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
    $currency = $tenant->currency_code ?: 'USD';
@endphp

<div>
    <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        <div class="col-xxl-6">
            <div class="card card-flush h-md-100">
                <div class="card-body d-flex flex-column justify-content-between p-9">
                    <div>
                        <span class="badge badge-light-primary fw-bold mb-4">Tenant workspace</span>
                        <h2 class="fs-2hx fw-bold text-gray-900 mb-3">{{ $tenant->business_name }}</h2>
                        <p class="fs-5 text-gray-600 mb-0 mw-650px">
                            Track the core spa operations: published services, booking pipeline, content readiness, and revenue from completed bookings.
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-3 mt-8">
                        <a href="{{ route('panel.services') }}" class="btn btn-primary">
                            Manage services
                            <i class="ki-duotone ki-arrow-right fs-3 ms-2"><span class="path1"></span><span class="path2"></span></i>
                        </a>
                        <a href="{{ route('tenant.preview', $tenant->subdomain) }}" target="_blank" class="btn btn-light-primary">
                            Open website preview
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xxl-3">
            <div class="card overflow-hidden h-md-100">
                <div class="card-body d-flex justify-content-between flex-column px-0 pb-0">
                    <div class="mb-4 px-9">
                        <div class="d-flex align-items-center mb-2">
                            <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1">{{ $counts['active_services'] }}</span>
                            <span class="badge badge-light-success fs-base">{{ $counts['hidden_services'] }} hidden</span>
                        </div>
                        <span class="fs-6 fw-semibold text-gray-500">Active online services</span>
                    </div>
                    <div class="d-flex align-items-end px-9 pb-8 gap-3">
                        @foreach ([65, 80, 48, 74, 92, 58, 84] as $height)
                            <div class="bg-primary rounded-top w-100" style="height: {{ $height }}px; opacity: {{ $loop->last ? '1' : '.18' }}"></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xxl-3">
            <div class="card card-flush h-md-100">
                <div class="card-body p-9">
                    <div class="d-flex align-items-center mb-7">
                        <span class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-warning">
                                <i class="ki-duotone ki-time fs-2x text-warning"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </span>
                        <div>
                            <div class="fs-2hx fw-bold text-gray-900">{{ $counts['pending_bookings'] }}</div>
                            <div class="fs-6 fw-semibold text-gray-500">Pending bookings</div>
                        </div>
                    </div>
                    <div class="d-flex flex-stack">
                        <span class="text-gray-600 fw-semibold">Total bookings</span>
                        <span class="badge badge-light-primary fs-base">{{ $counts['bookings'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        <div class="col-md-6 col-xl-3">
            <div class="card card-flush h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-6">
                        <span class="fw-semibold text-gray-500">Completed revenue</span>
                        <span class="symbol symbol-40px">
                            <span class="symbol-label bg-light-success">
                                <i class="ki-duotone ki-dollar fs-2 text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </span>
                        </span>
                    </div>
                    <div class="fs-2hx fw-bold text-gray-900">{{ $money->formatCurrency($counts['completed_revenue'], $currency) }}</div>
                    <div class="text-gray-600 fw-semibold mt-2">{{ $counts['completed_bookings'] }} completed bookings</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card card-flush h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-6">
                        <span class="fw-semibold text-gray-500">Confirmed bookings</span>
                        <span class="symbol symbol-40px">
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-calendar-tick fs-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </span>
                        </span>
                    </div>
                    <div class="fs-2hx fw-bold text-gray-900">{{ $counts['confirmed_bookings'] }}</div>
                    <div class="text-gray-600 fw-semibold mt-2">{{ $counts['cancelled_bookings'] }} cancelled</div>
                </div>
            </div>
        </div>

        @foreach ([
            ['label' => 'Packages', 'value' => $counts['packages'], 'icon' => 'ki-parcel', 'tone' => 'success'],
            ['label' => 'Staff', 'value' => $counts['staff'], 'icon' => 'ki-profile-user', 'tone' => 'info'],
            ['label' => 'Gallery', 'value' => $counts['gallery'], 'icon' => 'ki-picture', 'tone' => 'warning'],
        ] as $stat)
            <div class="col-md-6 col-xl-2">
                <div class="card card-flush h-100">
                    <div class="card-body">
                        <div class="symbol symbol-45px mb-5">
                            <span class="symbol-label bg-light-{{ $stat['tone'] }}">
                                <i class="ki-duotone {{ $stat['icon'] }} fs-2x text-{{ $stat['tone'] }}"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </span>
                        </div>
                        <div class="fs-2 fw-bold text-gray-900">{{ $stat['value'] }}</div>
                        <div class="fs-7 fw-semibold text-gray-500 text-uppercase">{{ $stat['label'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-5 g-xl-8">
        <div class="col-xl-5">
            <div class="card card-flush h-xl-100">
                <div class="card-header">
                    <h3 class="card-title fw-bold text-gray-900">Setup progress</h3>
                    <span class="badge badge-light-success">{{ $progress }}%</span>
                </div>
                <div class="card-body pt-0">
                    <div class="h-8px bg-light rounded mb-7">
                        <div class="bg-primary rounded h-8px" role="progressbar" style="width: {{ $progress }}%"></div>
                    </div>
                    <div class="d-flex flex-column gap-4">
                        @foreach ($setupSteps as $step)
                            <div class="d-flex align-items-center gap-3">
                                <span class="symbol symbol-30px">
                                    <span class="symbol-label {{ $step['complete'] ? 'bg-light-success text-success' : 'bg-light text-gray-500' }}">
                                        <i class="ki-duotone {{ $step['complete'] ? 'ki-check' : 'ki-minus' }} fs-3"></i>
                                    </span>
                                </span>
                                <span class="fw-semibold {{ $step['complete'] ? 'text-gray-900' : 'text-gray-600' }}">{{ $step['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card card-flush h-xl-100">
                <div class="card-header">
                    <h3 class="card-title fw-bold text-gray-900">Quick actions</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-4">
                        @foreach ([
                            ['label' => 'Services menu', 'href' => route('panel.services')],
                            ['label' => 'Bookings pipeline', 'href' => route('panel.bookings')],
                            ['label' => 'Statistics reports', 'href' => route('panel.statistics')],
                            ['label' => 'Reception desk', 'href' => route('panel.reception')],
                            ['label' => 'Website settings', 'href' => route('panel.settings')],
                        ] as $action)
                            <div class="col-md-6">
                                <a href="{{ $action['href'] }}" class="d-flex align-items-center justify-content-between border border-gray-300 border-dashed rounded p-5 bg-hover-light">
                                    <span class="fw-bold text-gray-800">{{ $action['label'] }}</span>
                                    <i class="ki-duotone ki-arrow-right fs-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
