<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(OrderObserver::class)]
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'identifier',
        'subtotal',
        'tax',
        'tipping_percentage',
        'tipping',
        'total',
        'status',
        'order_type',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'tipping_percentage' => 'decimal:2',
        'tipping' => 'decimal:2',
        'total' => 'decimal:2',
        'status' => OrderStatusEnum::class,
        'order_type' => OrderTypeEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function tables()
    {
        return $this->belongsToMany(Table::class, 'order_table');
    }
}
