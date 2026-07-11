<?php

namespace Tests\Feature;

use App\Livewire\Panel\Templates as PanelTemplates;
use App\Models\Tenant;
use App\Models\User;
use App\Models\WebsiteTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WebsiteTemplateManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_create_update_and_hide_template(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'password',
            'role' => 'super_admin',
        ]);

        $this->actingAs($admin)
            ->post('/admin/templates', [
                'name' => 'Luxury Dark',
                'slug' => '',
                'component' => 'Tenant/FirstTemplate',
                'description' => 'Premium dark spa template.',
                'preview_image_path' => '/template-kits/first-template/assets/logo.jpeg',
                'is_active' => true,
                'sort_order' => 1,
            ])
            ->assertRedirect();

        $template = WebsiteTemplate::where('slug', 'luxury-dark')->firstOrFail();
        $this->assertTrue($template->is_active);

        $this->actingAs($admin)
            ->put("/admin/templates/{$template->id}", [
                'name' => 'Luxury Dark Updated',
                'slug' => 'luxury-dark-updated',
                'component' => 'Tenant/FirstTemplate',
                'description' => 'Updated.',
                'preview_image_path' => '',
                'is_active' => false,
                'sort_order' => 2,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('website_templates', [
            'id' => $template->id,
            'name' => 'Luxury Dark Updated',
            'slug' => 'luxury-dark-updated',
            'is_active' => false,
        ]);
    }

    public function test_tenant_can_select_only_active_templates(): void
    {
        $active = WebsiteTemplate::create([
            'name' => 'First Template',
            'slug' => 'first-template',
            'component' => 'Tenant/FirstTemplate',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $hidden = WebsiteTemplate::create([
            'name' => 'Hidden Template',
            'slug' => 'hidden-template',
            'component' => 'Tenant/FirstTemplate',
            'is_active' => false,
            'sort_order' => 2,
        ]);

        [$tenant, $owner] = $this->tenantWithOwner();

        Livewire::actingAs($owner)
            ->test(PanelTemplates::class)
            ->call('confirmSwitch', $active->id)
            ->call('switchTemplate')
            ->assertHasNoErrors();

        $this->assertSame($active->id, $tenant->fresh()->website_template_id);

        Livewire::actingAs($owner)
            ->test(PanelTemplates::class)
            ->call('confirmSwitch', $hidden->id)
            ->assertNotFound();

        $this->assertSame($active->id, $tenant->fresh()->website_template_id);
    }

    /**
     * @return array{Tenant, User}
     */
    private function tenantWithOwner(): array
    {
        $tenant = Tenant::create([
            'business_name' => 'Calm Spa',
            'slug' => 'calm-spa',
            'subdomain' => 'calm-spa',
            'status' => 'active',
        ]);

        $owner = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Tenant Owner',
            'email' => 'owner@example.test',
            'email_verified_at' => now(),
            'password' => 'password',
            'role' => 'owner',
        ]);

        $tenant->update(['owner_user_id' => $owner->id]);

        return [$tenant, $owner];
    }
}
