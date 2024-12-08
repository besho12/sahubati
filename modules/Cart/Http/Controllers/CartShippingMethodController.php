<?php

namespace Modules\Cart\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Cart\Facades\Cart;
use Modules\Shipping\Facades\ShippingMethod;
use Modules\ShippingArea\Entities\ShippingArea;

class CartShippingMethodController
{
    /**
     * Store a newly created resource in storage.
     *
     * @return \Modules\Cart\Cart
     */
    public function store()
    {
        Cart::addShippingMethod(
            ShippingMethod::get(
                request('shipping_method')
            )
        );

        return Cart::instance();
    }

    public function getAreaCost(Request $request){
        return ShippingArea::where('slug',$request->area)->first()['cost'];

    }
}
