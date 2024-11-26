<?php

namespace Modules\ShippingArea\Http\Controllers;

use Illuminate\Http\Response;

class HomeController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('storefront::public.home.index');
    }
}
