<?php

namespace App\Observers;

use App\Enums\OrderStatusEnum;
use App\Models\Order;

class OrderObserver
{
    public function creating(Order $order)
    {
        if (empty($order->identifier)) {
            $date = now()->format('Ymd');
            $count = Order::whereDate('created_at', now())->count() + 1;
            $lenght = 4;
            if ($count > 9999) {
                $lenght = strlen($count); // strlen(10000) = 5, so it will pad to 5 digits
            }
            $dailySequence = str_pad($count, $lenght, '0', STR_PAD_LEFT);
            $order->identifier = "{$date}-{$dailySequence}";
        }

        // Set default status if not provided
        if (empty($order->status)) {
            $order->status = OrderStatusEnum::PENDING;
        }
    }
}
