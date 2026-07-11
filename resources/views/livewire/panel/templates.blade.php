@php
    $currentTemplate = $templates->firstWhere('id', $currentTemplateId) ?? $tenant->websiteTemplate;
    $imageUrl = static function (?string $path): ?string {
        if (! $path) {
            return null;
        }

        return str_starts_with($path, 'http://') || str_starts_with($path, 'https://')
            ? $path
            : $path;
    };
@endphp

<div>
    <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        <div class="col-xl-8">
            <div class="card card-flush h-xl-100">
                <div class="card-body p-9">
                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-8">
                        <div>
                            <span class="badge badge-light-primary fw-bold mb-4">Website templates</span>
                            <h2 class="fs-2hx fw-bold text-gray-900 mb-3">Choose the public website look.</h2>
                            <p class="fs-5 text-gray-600 mb-0 mw-650px">
                                These previews are full website screenshots. The card shows the top of each page, and the preview button opens the full long screenshot in a scrollable viewer.
                            </p>
                        </div>
                        <div class="d-flex flex-column gap-3 min-w-lg-250px">
                            <a href="{{ $previewUrl }}" target="_blank" class="btn btn-primary">
                                Open live website
                                <i class="ki-duotone ki-arrow-right fs-3 ms-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <a href="{{ route('panel.settings') }}" class="btn btn-light-primary">Edit website content</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card card-flush h-xl-100">
                <div class="card-body p-9">
                    <span class="symbol symbol-50px mb-6">
                        <span class="symbol-label bg-light-success">
                            <i class="ki-duotone ki-design fs-2x text-success"><span class="path1"></span><span class="path2"></span></i>
                        </span>
                    </span>
                    <div class="fs-6 fw-semibold text-gray-500 mb-2">Current template</div>
                    <div class="fs-2 fw-bold text-gray-900 mb-3">{{ $currentTemplate?->name ?? 'No template selected' }}</div>
                    <div class="text-gray-600">{{ $templates->count() }} active templates available.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-flush mb-5 mb-xl-8">
        <div class="card-body p-6">
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-4">
                <div class="position-relative flex-grow-1">
                    <i class="ki-outline ki-magnifier fs-2 text-gray-500 position-absolute top-50 translate-middle-y ms-5"></i>
                    <input type="text" wire:model.live.debounce.250ms="query" class="form-control ps-13" placeholder="Search templates by name, slug, or style">
                </div>
                <span class="badge badge-light-primary fs-7">{{ $templates->count() }} shown</span>
            </div>
        </div>
    </div>

    @if ($templates->isNotEmpty())
        <div class="row g-5 g-xl-8">
            @foreach ($templates as $template)
                @php
                    $selected = (int) $currentTemplateId === (int) $template->id;
                    $preview = $imageUrl($template->preview_image_path);
                @endphp

                <div class="col-xl-6 col-xxl-4" wire:key="template-{{ $template->id }}">
                    <div class="card card-flush h-100 {{ $selected ? 'border border-primary' : '' }}">
                        <div class="card-body p-0">
                            <div class="p-4 pb-0">
                                <div class="rounded-top border bg-gray-200 overflow-hidden">
                                    <div class="d-flex align-items-center gap-2 px-4 py-3 bg-gray-100 border-bottom">
                                        <span class="bullet bullet-dot bg-danger"></span>
                                        <span class="bullet bullet-dot bg-warning"></span>
                                        <span class="bullet bullet-dot bg-success"></span>
                                        <span class="ms-3 fs-8 fw-semibold text-gray-500 text-truncate">{{ $template->slug }}</span>
                                    </div>
                                    <div class="position-relative bg-white">
                                        @if ($preview)
                                            <img src="{{ $preview }}" alt="{{ $template->name }} full website screenshot" class="w-100 object-fit-cover" style="height: 430px; object-position: top center;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center text-gray-500" style="height: 430px;">
                                                <i class="ki-duotone ki-design fs-4x"><span class="path1"></span><span class="path2"></span></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute bottom-0 start-0 end-0 p-4" style="background: linear-gradient(0deg, rgba(0,0,0,.55), transparent);">
                                            <div class="d-flex justify-content-between align-items-end gap-3">
                                                <span class="badge badge-light-dark">{{ $template->slug }}</span>
                                                @if ($selected)
                                                    <span class="badge badge-light-success">
                                                        <i class="ki-duotone ki-check fs-4 me-1"></i>
                                                        Current
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-7">
                                <h3 class="fs-3 fw-bold text-gray-900 mb-3">{{ $template->name }}</h3>
                                <p class="text-gray-600 min-h-70px">{{ $template->description ?? 'Premium Spaio website template.' }}</p>

                                <div class="d-flex flex-wrap gap-3 mt-6">
                                    @if ($preview)
                                        <button type="button" wire:click="previewScreenshot({{ $template->id }})" class="btn btn-light-primary">
                                            View full screenshot
                                        </button>
                                    @endif

                                    @if ($selected)
                                        <button type="button" class="btn btn-light-success" disabled>Current template</button>
                                    @else
                                        <button type="button" wire:click="confirmSwitch({{ $template->id }})" class="btn btn-primary">
                                            Use this template
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card card-flush">
            <div class="card-body text-center py-15">
                <div class="symbol symbol-80px mb-6">
                    <span class="symbol-label bg-light-primary">
                        <i class="ki-duotone ki-design fs-3x text-primary"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                </div>
                <h3 class="fw-bold text-gray-900">No templates found</h3>
                <p class="text-gray-600 mb-0">Try another search term or ask the platform admin to activate templates.</p>
            </div>
        </div>
    @endif

    <div wire:ignore.self class="modal fade" id="templateSwitchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-body text-center py-15 px-10">
                    <div class="symbol symbol-90px mb-7">
                        <span class="symbol-label bg-light-primary">
                            <i class="ki-duotone ki-design fs-3x text-primary"><span class="path1"></span><span class="path2"></span></i>
                        </span>
                    </div>

                    <h2 class="fw-bold text-gray-900 mb-3">Switch website template?</h2>
                    <p class="text-gray-600 fs-6 mb-8">
                        Your public website will use
                        <span class="fw-bold text-gray-900">{{ $selectedTemplateName ?: 'this template' }}</span>.
                        Services, packages, staff, gallery, and bookings stay connected.
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" wire:click="cancelSwitch" class="btn btn-light">Cancel</button>
                        <button type="button" wire:click="switchTemplate" class="btn btn-primary" wire:loading.attr="disabled" wire:target="switchTemplate">
                            <span wire:loading.remove wire:target="switchTemplate">Switch template</span>
                            <span wire:loading wire:target="switchTemplate">Switching...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="templatePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h2 class="fw-bold mb-1">{{ $previewTemplate?->name ?? 'Template screenshot' }}</h2>
                        <div class="text-gray-600 fs-7">Full website screenshot preview.</div>
                    </div>
                    <button type="button" wire:click="closePreview" class="btn btn-icon btn-sm btn-active-icon-primary" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </button>
                </div>
                <div class="modal-body bg-gray-100 p-4">
                    @if ($previewTemplate?->preview_image_path)
                        <div class="bg-white rounded border overflow-auto mx-auto" style="max-height: 72vh;">
                            <img src="{{ $imageUrl($previewTemplate->preview_image_path) }}" alt="{{ $previewTemplate->name }} full website screenshot" class="w-100 h-auto d-block">
                        </div>
                    @else
                        <div class="text-center py-15 text-gray-600">No screenshot available.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @script
        <script>
            const templateSwitchModalElement = document.getElementById('templateSwitchModal');

            $wire.on('template-switch-modal:show', () => {
                bootstrap.Modal.getOrCreateInstance(templateSwitchModalElement).show();
            });

            $wire.on('template-switch-modal:hide', () => {
                bootstrap.Modal.getOrCreateInstance(templateSwitchModalElement).hide();
            });

            const templatePreviewModalElement = document.getElementById('templatePreviewModal');

            $wire.on('template-preview-modal:show', () => {
                bootstrap.Modal.getOrCreateInstance(templatePreviewModalElement).show();
            });

            $wire.on('template-preview-modal:hide', () => {
                bootstrap.Modal.getOrCreateInstance(templatePreviewModalElement).hide();
            });
        </script>
    @endscript
</div>
