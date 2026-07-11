<?php

namespace App\Livewire\Panel;

use App\Models\WebsiteTemplate;
use App\Support\TenantPermissions;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Templates extends Component
{
    public string $query = '';

    public ?int $selectedTemplateId = null;

    public string $selectedTemplateName = '';

    public ?int $previewTemplateId = null;

    public function render(): View
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'templates.change'), 403);

        $tenant = auth()->user()?->tenant;
        abort_unless($tenant, 403);

        return view('livewire.panel.templates', [
            'tenant' => $tenant,
            'templates' => $this->templates(),
            'currentTemplateId' => $tenant->website_template_id,
            'previewUrl' => route('tenant.preview', $tenant->subdomain),
            'previewTemplate' => $this->previewTemplate(),
        ])->layout('components.layouts.panel', [
            'title' => 'Templates',
        ]);
    }

    public function confirmSwitch(int $templateId): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'templates.change'), 403);

        $template = $this->findActiveTemplate($templateId);

        $this->selectedTemplateId = $template->id;
        $this->selectedTemplateName = $template->name;

        $this->dispatch('template-switch-modal:show');
    }

    public function switchTemplate(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'templates.change'), 403);
        abort_unless($this->selectedTemplateId, 404);

        $template = $this->findActiveTemplate($this->selectedTemplateId);
        $tenant = auth()->user()?->tenant;
        abort_unless($tenant, 403);

        $tenant->update(['website_template_id' => $template->id]);

        $this->reset(['selectedTemplateId', 'selectedTemplateName']);
        $this->dispatch('template-switch-modal:hide');
        session()->flash('success', "Template switched to {$template->name}.");
    }

    public function cancelSwitch(): void
    {
        $this->reset(['selectedTemplateId', 'selectedTemplateName']);
        $this->dispatch('template-switch-modal:hide');
    }

    public function previewScreenshot(int $templateId): void
    {
        $template = $this->findActiveTemplate($templateId);

        $this->previewTemplateId = $template->id;
        $this->dispatch('template-preview-modal:show');
    }

    public function closePreview(): void
    {
        $this->reset('previewTemplateId');
        $this->dispatch('template-preview-modal:hide');
    }

    /**
     * @return Collection<int, WebsiteTemplate>
     */
    private function templates(): Collection
    {
        return WebsiteTemplate::query()
            ->where('is_active', true)
            ->when($this->query !== '', function ($query): void {
                $search = '%'.str($this->query)->lower()->toString().'%';

                $query->where(function ($query) use ($search): void {
                    $query
                        ->whereRaw('LOWER(name) LIKE ?', [$search])
                        ->orWhereRaw('LOWER(slug) LIKE ?', [$search])
                        ->orWhereRaw('LOWER(description) LIKE ?', [$search]);
                });
            })
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug', 'description', 'preview_image_path']);
    }

    private function previewTemplate(): ?WebsiteTemplate
    {
        if (! $this->previewTemplateId) {
            return null;
        }

        return WebsiteTemplate::query()
            ->where('is_active', true)
            ->find($this->previewTemplateId);
    }

    private function findActiveTemplate(int $templateId): WebsiteTemplate
    {
        $template = WebsiteTemplate::query()
            ->whereKey($templateId)
            ->where('is_active', true)
            ->first();

        abort_unless($template, 404);

        return $template;
    }
}
