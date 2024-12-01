<?php

namespace Modules\ShippingArea\Providers;

use Modules\ShippingArea\Admin\ShippingAreaTabs;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Admin\Ui\Facades\TabManager;
use Modules\ShippingArea\Http\Controllers\ShippingAreaController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ShippingAreaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        TabManager::register('shipping_areas', ShippingAreaTabs::class);

        $this->registerShippingAreaRoute();
    }


    private function registerShippingAreaRoute()
    {
        $this->app->booted(function () {
            Route::get('{slug}', [ShippingAreaController::class, 'show'])
                ->prefix(LaravelLocalization::setLocale())
                ->middleware(['localize', 'locale_session_redirect', 'localization_redirect', 'web'])
                ->name('shipping_areas.show');
        });
    }
}
