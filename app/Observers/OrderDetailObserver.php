<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderDetail;

class OrderDetailObserver
{
    public function created(OrderDetail $orderDetail)
    {
        $this->updateOrder($orderDetail->order);
    }

    public function updated(OrderDetail $orderDetail)
    {
        $this->updateOrder($orderDetail->order);
    }

    public function deleted(OrderDetail $orderDetail)
    {
        $this->updateOrder($orderDetail->order);
    }

    public function restored(OrderDetail $orderDetail)
    {
        $this->updateOrder($orderDetail->order);
    }

    private function updateOrder(Order $order): void
    {
        $vatRate = config('app.vat_rate');

        $order->subtotal = $order->orderDetails->sum('subtotal');
        $order->tax = $order->subtotal * $vatRate;
        $order->tipping_percentage = $order->tipping_percentage ?? 0;
        $order->tipping = $order->tipping_percentage > 0 ? $order->subtotal * $order->tipping_percentage : 0;
        $order->total = $order->subtotal + $order->tax + $order->tipping;
        $order->save();
    }
}
