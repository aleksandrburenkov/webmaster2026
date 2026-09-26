<?php

namespace App\Providers;

use App\Models\ContactSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer(['pages.home', 'layouts.app'], function ($view) {
            $contactsSettings = Cache::remember('contacts_settings_contacts', 3600, function () {
                return ContactSetting::getActiveContacts('contacts');
            });

            $footerSettings = Cache::remember('contacts_settings_footer', 3600, function () {
                return ContactSetting::getActiveContacts('footer');
            });

            $view->with('contactsSettings', $contactsSettings);
            $view->with('footerSettings', $footerSettings);
        });
    }
}
