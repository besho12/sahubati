<?php

namespace Modules\ShippingArea\Sidebar;

use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\Group;
use Modules\Admin\Sidebar\BaseSidebarExtender;

class SidebarExtender extends BaseSidebarExtender
{
    public function extend(Menu $menu)
    {
        $menu->group(trans('admin::sidebar.content'), function (Group $group) {
            $group->item('ShippingArea', function (Item $item) {
                $item->icon('fa fa-map');
                $item->weight(25);
                $item->route('admin.shipping_areas.index');
                $item->authorize(
                    $this->auth->hasAccess('admin.shipping_areas.index')
                );
            });
        });
    }
}
