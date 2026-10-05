<?php

namespace Tests\Feature;

use App\Services\MaintenanceModeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenanceModeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_site_is_available_when_maintenance_mode_is_disabled(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_public_site_returns_503_when_maintenance_mode_is_enabled(): void
    {
        app(MaintenanceModeService::class)->enable();

        $response = $this->get('/');

        $response->assertStatus(503);
        $response->assertSee('Технические работы');
    }

    public function test_admin_panel_login_is_not_blocked_when_maintenance_mode_is_enabled(): void
    {
        app(MaintenanceModeService::class)->enable();

        $this->get('/admin/login')->assertStatus(200);
    }

    public function test_maintenance_mode_state_is_stored_in_database(): void
    {
        $service = app(MaintenanceModeService::class);

        $service->enable();

        $this->assertDatabaseHas('settings', [
            'key' => MaintenanceModeService::SETTING_KEY,
            'value' => '1',
        ]);

        $this->assertTrue($service->isEnabled());

        $service->disable();

        $this->assertDatabaseHas('settings', [
            'key' => MaintenanceModeService::SETTING_KEY,
            'value' => '0',
        ]);

        $this->assertFalse($service->isEnabled());
    }

    public function test_public_site_is_available_again_after_maintenance_mode_is_disabled(): void
    {
        $service = app(MaintenanceModeService::class);

        $service->enable();
        $this->get('/')->assertStatus(503);

        $service->disable();
        $this->get('/')->assertStatus(200);
    }
}
