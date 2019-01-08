<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // get all settings
        $settings = [];
        foreach ( \DB::table('admin_settings')->get() as $setting ) {
            $settings[$setting->key] = $setting->value;
        }
        \Config::set('settings', $settings);

        // set stripe stuff
        \Stripe\Stripe::setApiKey($settings['stripe_secret_key'] ?: env('STRIPE_SECRET_KEY'));

    }
}
