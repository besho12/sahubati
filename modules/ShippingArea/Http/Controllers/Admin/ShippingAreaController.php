<?php

namespace Modules\ShippingArea\Http\Controllers\Admin;

use Modules\ShippingArea\Entities\ShippingArea;
use Modules\Admin\Traits\HasCrudActions;
use Modules\ShippingArea\Http\Requests\SaveShippingAreaRequest;

class ShippingAreaController
{
    use HasCrudActions;

    /**
     * Model for the resource.
     *
     * @var string
     */
    protected $model = ShippingArea::class;

    /**
     * Label of the resource.
     *
     * @var string
     */
    protected $label = 'shippingarea::pages.shippingarea';

    /**
     * View path of the resource.
     *
     * @var string
     */
    protected $viewPath = 'shippingarea::admin.shipping_areas';

    /**
     * Form requests for the resource.
     *
     * @var array|string
     */
    protected $validation = SaveShippingAreaRequest::class;
}
