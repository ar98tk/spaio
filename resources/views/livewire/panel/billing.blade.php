@php
    $money = static fn ($value) => '$'.number_format((float) ($value ?? 0), ((float) ($value ?? 0)) == floor((float) ($value ?? 0)) ? 0 : 2);
    $formatDate = static fn ($value) => $value ? $value->format('M j, Y') : 'Not set';
    $statusTone = static fn (?string $status): string => match ($status) {
        'active' => 'success',
        'trialing' => 'primary',
        'past_due' => 'warning',
        'cancelled' => 'danger',
        default => 'secondary',
    };
    $currentPlan = $subscription?->plan;
    $trialProgress = $trial['is_trialing']
        ? max(4, min(100, ((10 - (int) $trial['days_remaining']) / 10) * 100))
        : 100;
@endphp

<div>
    <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        <div class="col-xl-5">
            <div class="card card-flush h-100 overflow-hidden">
                <div class="card-body p-0">
                    <div class="p-8 text-white" style="background: linear-gradient(135deg, #17345f 0%, #0f766e 100%);">
                        <div class="d-flex align-items-center justify-content-between gap-4 mb-7">
                            <span class="badge badge-light-{{ $statusTone($subscription?->status ?? 'trialing') }} text-uppercase fw-bold">
                                {{ $subscription?->status ?? 'trialing' }}
                            </span>
                            <span class="symbol symbol-45px">
                                <span class="symbol-label bg-white bg-opacity-25">
                                    <i class="ki-duotone ki-credit-cart fs-2 text-white"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </span>
                        </div>

                        <h2 class="fw-bold text-white fs-1 mb-2">{{ $currentPlan?->name ?? 'Starter' }}</h2>
                        <p class="text-white opacity-75 mb-7">{{ $currentPlan?->description ?? 'Your account is ready with trial access while you choose the best plan.' }}</p>

                        <div class="row g-4">
                            <div class="col-sm-6">
                                <div class="rounded border border-white border-opacity-25 bg-white bg-opacity-10 p-4">
                                    <div class="fs-8 text-white opacity-75 text-uppercase fw-bold mb-1">Billing cycle</div>
                                    <div class="fs-4 fw-bold text-white text-capitalize">{{ $subscription?->billing_cycle ?? $billingCycle }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="rounded border border-white border-opacity-25 bg-white bg-opacity-10 p-4">
                                    <div class="fs-8 text-white opacity-75 text-uppercase fw-bold mb-1">{{ $trial['is_trialing'] ? 'Trial ends' : 'Renews on' }}</div>
                                    <div class="fs-4 fw-bold text-white">{{ $formatDate($trial['ends_at'] ?? $subscription?->current_period_ends_at) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-7">
                        @if ($trial['is_trialing'])
                            <div class="d-flex align-items-start gap-4">
                                <span class="symbol symbol-45px">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-timer fs-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </span>
                                </span>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="fw-bold text-gray-900">Free trial is active</div>
                                        <span class="badge badge-light-primary">{{ $trial['days_remaining'] }} days left</span>
                                    </div>
                                    <div class="progress h-8px bg-light-primary mb-3">
                                        <div class="progress-bar bg-primary" style="width: {{ $trialProgress }}%"></div>
                                    </div>
                                    <div class="text-gray-600 fs-7">Choose a plan before {{ $formatDate($trial['ends_at']) }} to keep the website active.</div>
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-start gap-4">
                                <span class="symbol symbol-45px">
                                    <span class="symbol-label bg-light-success">
                                        <i class="ki-duotone ki-shield-tick fs-2 text-success"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                </span>
                                <div>
                                    <div class="fw-bold text-gray-900 mb-1">Subscription is active</div>
                                    <div class="text-gray-600 fs-7">Your plan renews on {{ $formatDate($subscription?->current_period_ends_at) }}.</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card card-flush h-100">
                <div class="card-header">
                    <div>
                        <h3 class="card-title fw-bold text-gray-900">Billing documents</h3>
                        <div class="text-gray-600 fs-7">Download invoices for your records.</div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if ($invoices)
                        <div class="d-flex flex-column gap-3">
                            @foreach ($invoices as $invoice)
                                <div class="border border-gray-200 rounded px-5 py-4">
                                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
                                        <div>
                                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                                <span class="fw-bold text-gray-900">{{ $invoice['number'] }}</span>
                                                <span class="badge badge-light-{{ $statusTone($invoice['status']) }}">{{ $invoice['status'] }}</span>
                                            </div>
                                            <div class="text-gray-600 fs-7">
                                                {{ $invoice['description'] }} · Issued {{ $formatDate($invoice['issued_at']) }} · {{ $money($invoice['amount']) }}
                                            </div>
                                        </div>
                                        <a href="{{ $invoice['download_url'] }}" class="btn btn-sm btn-light-primary">
                                            <i class="ki-duotone ki-file-down fs-3"><span class="path1"></span><span class="path2"></span></i>
                                            PDF
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="symbol symbol-80px mb-5">
                                <span class="symbol-label bg-light-primary">
                                    <i class="ki-duotone ki-document fs-3x text-primary"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <h3 class="fw-bold text-gray-900">No invoices yet</h3>
                            <p class="text-gray-600 mb-0">Invoices will appear here after the first paid billing cycle starts.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card card-flush">
        <div class="card-header align-items-center">
            <div>
                <h3 class="card-title fw-bold text-gray-900">Choose the right plan</h3>
                <div class="text-gray-600 fs-7">Upgrade when you need more capacity, templates, reports, or staff access.</div>
            </div>
            <div class="btn-group">
                @foreach (['monthly', 'yearly'] as $cycle)
                    <button type="button" wire:click="setBillingCycle('{{ $cycle }}')" class="btn btn-sm text-capitalize {{ $billingCycle === $cycle ? 'btn-primary' : 'btn-light' }}">
                        {{ $cycle }}
                    </button>
                @endforeach
            </div>
        </div>
        <div class="card-body pt-0">
            @if ($plans->isNotEmpty())
                <div class="row g-5">
                    @foreach ($plans as $plan)
                        @php
                            $price = $billingCycle === 'yearly' ? $plan->yearly_price : $plan->monthly_price;
                            $isCurrent = $currentPlan?->id === $plan->id && $subscription?->billing_cycle === $billingCycle;
                        @endphp
                        <div class="col-xl-4" wire:key="billing-plan-{{ $plan->id }}">
                            <div class="card card-flush border h-100 {{ $plan->is_featured ? 'border-primary' : 'border-gray-300' }}">
                                <div class="card-body p-7">
                                    <div class="d-flex align-items-start justify-content-between gap-4 mb-5">
                                        <div>
                                            <h4 class="fw-bold text-gray-900 mb-2">{{ $plan->name }}</h4>
                                            @if ($plan->is_featured)
                                                <span class="badge badge-light-primary">Popular</span>
                                            @endif
                                        </div>
                                        <span class="symbol symbol-45px">
                                            <span class="symbol-label bg-light-primary">
                                                <i class="ki-duotone ki-crown fs-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            </span>
                                        </span>
                                    </div>

                                    <div class="mb-5">
                                        <span class="fs-2hx fw-bold text-gray-900">{{ $money($price) }}</span>
                                        <span class="text-gray-600">/{{ $billingCycle === 'yearly' ? 'year' : 'month' }}</span>
                                    </div>

                                    <p class="text-gray-600 min-h-50px">{{ $plan->description }}</p>

                                    <div class="separator my-5"></div>

                                    <div class="d-flex flex-column gap-3 mb-7">
                                        @foreach (($plan->features ?? []) as $feature)
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ki-duotone ki-check-circle fs-3 text-success"><span class="path1"></span><span class="path2"></span></i>
                                                <span class="text-gray-700">{{ $feature }}</span>
                                            </div>
                                        @endforeach
                                    </div>

                                    <button
                                        type="button"
                                        wire:click="selectPlan({{ $plan->id }})"
                                        class="btn w-100 {{ $isCurrent ? 'btn-light' : 'btn-primary' }}"
                                        @disabled($isCurrent)
                                        wire:loading.attr="disabled"
                                        wire:target="selectPlan({{ $plan->id }})"
                                    >
                                        <span wire:loading.remove wire:target="selectPlan({{ $plan->id }})">
                                            {{ $isCurrent ? 'Current plan' : 'Select plan' }}
                                        </span>
                                        <span wire:loading wire:target="selectPlan({{ $plan->id }})">Updating...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <h3 class="fw-bold text-gray-900">No plans available</h3>
                    <p class="text-gray-600 mb-0">Plans will appear here after the platform admin creates them.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="row g-5 mt-1">
        <div class="col-md-4">
            <div class="card card-flush h-100">
                <div class="card-body p-5">
                    <div class="fw-bold text-gray-900 mb-1">Immediate changes</div>
                    <div class="text-gray-600 fs-7">Plan changes update the tenant account immediately.</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-flush h-100">
                <div class="card-body p-5">
                    <div class="fw-bold text-gray-900 mb-1">Invoice generation</div>
                    <div class="text-gray-600 fs-7">PDF generation and invoice email queueing are connected.</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-flush h-100">
                <div class="card-body p-5">
                    <div class="fw-bold text-gray-900 mb-1">Payment gateway</div>
                    <div class="text-gray-600 fs-7">Online payment collection remains for the production billing step.</div>
                </div>
            </div>
        </div>
    </div>
</div>
