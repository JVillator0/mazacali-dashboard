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
        $order->subtotal = $order->orderDetails->sum('subtotal');
        $order->tax_included = $order->tax_included ?? false;
        $order->tax = $order->tax_included ? 0 : $order->subtotal * config('app.vat_rate');
        $order->tipping_percentage = $order->tipping_percentage ?? 0;
        $order->tipping = $order->tipping_percentage > 0 ? $order->subtotal * $order->tipping_percentage : 0;
        $order->total = $order->subtotal + $order->tax + $order->tipping;
        $order->save();
    }
}
