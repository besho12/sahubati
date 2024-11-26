<?php

namespace Modules\ShippingArea\Admin;

use Modules\Admin\Ui\Tab;
use Modules\Admin\Ui\Tabs;

class ShippingAreaTabs extends Tabs
{
    public function make()
    {
        $this->group('shippingarea_information', trans('shippingarea::shippingareas.tabs.group.shippingarea_information'))
            ->active()
            ->add($this->general())
            ->add($this->seo());
    }


    private function general()
    {
        return tap(new Tab('general', trans('shippingarea::shippingareas.tabs.general')), function (Tab $tab) {
            $tab->active();
            $tab->weight(5);
            $tab->fields(['title', 'body', 'is_active', 'slug']);
            $tab->view('shippingarea::admin.shippingareas.tabs.general');
        });
    }


    private function seo()
    {
        return tap(new Tab('seo', trans('shippingarea::shippingareas.tabs.seo')), function (Tab $tab) {
            $tab->weight(10);
            $tab->view('shippingarea::admin.shippingareas.tabs.seo');
        });
    }
}
