<?php

namespace Database\Seeders;

use App\Models\ContactSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ContactSettingsSeeder extends Seeder
{
    private const ICONS_PATH = 'icons/contacts/';

    public function run(): void
    {
        $this->ensureIconsExist();

        ContactSetting::truncate();

        $contactsBlock = [
            [
                'type' => 'max',
                'label' => 'Max',
                'icon_file' => self::ICONS_PATH . 'icon-max.svg',
                'url' => 'https://max.ru/webmaster32',
                'display_order' => 1,
                'block' => 'contacts',
                'custom_attributes' => ['target' => '_blank', 'rel' => 'noopener noreferrer'],
            ],
            [
                'type' => 'email',
                'label' => 'Email',
                'icon_file' => self::ICONS_PATH . 'icon-email.svg',
                'url' => 'mailto:admin@webmaster32.ru',
                'display_order' => 2,
                'block' => 'contacts',
                'custom_attributes' => null,
            ],
        ];

        $contactsInfo = [
            [
                'type' => 'email',
                'label' => 'admin@webmaster32.ru',
                'icon_file' => null,
                'url' => 'mailto:admin@webmaster32.ru',
                'value' => 'admin@webmaster32.ru',
                'display_order' => 3,
                'block' => 'contacts',
                'custom_attributes' => null,
            ],
            [
                'type' => 'site',
                'label' => 'webmaster32.ru',
                'icon_file' => null,
                'url' => 'https://webmaster32.ru',
                'value' => 'webmaster32.ru',
                'display_order' => 4,
                'block' => 'contacts',
                'custom_attributes' => ['target' => '_blank', 'rel' => 'noopener'],
            ],
        ];

        $footerSocial = [
            [
                'type' => 'max',
                'label' => 'Max',
                'icon_file' => self::ICONS_PATH . 'icon-max.svg',
                'url' => 'https://max.ru/webmaster32',
                'display_order' => 1,
                'block' => 'footer',
                'custom_attributes' => ['target' => '_blank', 'rel' => 'noopener noreferrer'],
            ],
            [
                'type' => 'vk',
                'label' => 'ВКонтакте',
                'icon_file' => self::ICONS_PATH . 'icon-vk.svg',
                'url' => 'https://vk.com/webmaster32',
                'display_order' => 2,
                'block' => 'footer',
                'custom_attributes' => ['target' => '_blank', 'rel' => 'noopener noreferrer'],
            ],
            [
                'type' => 'email',
                'label' => 'Email',
                'icon_file' => self::ICONS_PATH . 'icon-email.svg',
                'url' => 'mailto:admin@webmaster32.ru',
                'display_order' => 3,
                'block' => 'footer',
                'custom_attributes' => null,
            ],
        ];

        foreach (array_merge($contactsBlock, $contactsInfo, $footerSocial) as $data) {
            ContactSetting::create(array_merge(['is_active' => true], $data));
        }
    }

    private function ensureIconsExist(): void
    {
        $icons = ['icon-max.svg', 'icon-email.svg', 'icon-vk.svg'];
        $diskPath = storage_path('app/public/' . self::ICONS_PATH);

        if (!File::isDirectory($diskPath)) {
            File::makeDirectory($diskPath, 0755, true);
        }

        foreach ($icons as $icon) {
            $targetPath = $diskPath . $icon;
            if (!File::exists($targetPath)) {
                \Illuminate\Support\Facades\Log::warning("Icon file not found during seed: {$targetPath}");
            }
        }
    }
}