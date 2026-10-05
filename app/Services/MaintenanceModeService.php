<?php

namespace App\Services;

use App\Models\Setting;

class MaintenanceModeService
{
    public const SETTING_KEY = 'maintenance_mode';

    public function isEnabled(): bool
    {
        return Setting::get(self::SETTING_KEY, '0') === '1';
    }

    public function enable(bool $enabled = true): void
    {
        Setting::set(self::SETTING_KEY, $enabled ? '1' : '0');
    }

    public function disable(): void
    {
        $this->enable(false);
    }
}
