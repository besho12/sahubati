<?php

namespace Modules\Shipping\Providers;

use Modules\Shipping\Method;
use Illuminate\Support\ServiceProvider;
use Modules\Shipping\Facades\ShippingMethod;

class ShippingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if (!config('app.installed')) {
            return;
        }

        $this->registerFreeShipping();
        $this->registerLocalPickup();
        $this->registerFlatRate();
        $this->registerShippingRate();
    }


    private function registerFreeShipping()
    {
        if (!setting('free_shipping_enabled')) {
            return;
        }

        ShippingMethod::register('free_shipping', function () {
            return new Method('free_shipping', setting('free_shipping_label'), 0);
        });
    }


    private function registerLocalPickup()
    {
        if (!setting('local_pickup_enabled')) {
            return;
        }

        ShippingMethod::register('local_pickup', function () {
            return new Method('local_pickup', setting('local_pickup_label'), setting('local_pickup_cost') ?? 0);
        });
    }


    private function registerFlatRate()
    {
        if (!setting('flat_rate_enabled')) {
            return;
        }

        ShippingMethod::register('flat_rate', function () {
            return new Method('flat_rate', setting('flat_rate_label'), setting('flat_rate_cost') ?? 0);
        });
    }


    private function registerShippingRate()
    {
        if (!setting('shipping_rate_enabled')) {
            return;
        }

        $shipping_cost = $this->get_shipping_cost();

        ShippingMethod::register('shipping_rate', function () use($shipping_cost){
            return new Method('shipping_rate', setting('shipping_rate_label'), $shipping_cost ?? 0);
        });
    }

    private function get_shipping_cost(){
        // return '60';
    }
    
}
