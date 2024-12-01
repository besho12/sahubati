<?php

namespace Modules\ShippingArea\Admin;

use Modules\Admin\Ui\Tab;
use Modules\Admin\Ui\Tabs;

class ShippingAreaTabs extends Tabs
{
    public function make()
    {
        $this->group('shippingarea_information', trans('shippingarea::shipping_areas.tabs.group.shippingarea_information'))
            ->active()
            ->add($this->general());
    }


    private function general()
    {
        return tap(new Tab('general', trans('shippingarea::shipping_areas.tabs.general')), function (Tab $tab) {
            $tab->active();
            $tab->weight(5);
            $tab->fields(['title', 'cost' , 'is_active', 'slug']);
            $tab->view('shippingarea::admin.shipping_areas.tabs.general');
        });
    }

}
