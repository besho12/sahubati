<?php

namespace Modules\Checkout\Listeners;

use Modules\Order\Entities\Order;
use Modules\Checkout\Events\OrderPlaced;

class UpdateOrderStatus
{
    /**
     * Handle the event.
     *
     * @param OrderPlaced $event
     *
     * @return void
     */
    public function handle($event)
    {
        if($event->order->getAttributes()['payment_method'] != 'paylink'){
            $event->order->update(['status' => Order::PENDING]);
        }
    }
}
