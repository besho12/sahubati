<?php

namespace Modules\ShippingArea\Http\Controllers;

use Illuminate\Http\Response;
use Modules\ShippingArea\Entities\ShippingArea;
use Modules\Media\Entities\File;

class ShippingAreaController
{
    /**
     * Display shippingArea for the slug.
     *
     * @param string $slug
     *
     * @return Response
     */
    public function show($slug)
    {
        $logo = File::findOrNew(setting('storefront_header_logo'))->path;
        $shippingArea = ShippingArea::where('slug', $slug)->firstOrFail();

        return view('storefront::public.shipping_areas.show', compact('shippingarea', 'logo'));
    }
}
