<?php

namespace Elfcms\Shop\Models;

use Elfcms\Elfcms\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'payment_method_id',
        'delivery_id',
        'order_status_id',
        'start_sum',
        'discount_sum',
        'clear_sum',
        'delivery_sum',
        'result_sum',
        'item_quantity',
        'total_quantity',
        'tracking_code',
        'tracking_link',
    ];

    public function invoces()
    {
        return $this->hasMany(ShopInvoice::class, 'order_id');
    }

    public function items()
    {
        return $this->belongsToMany(ShopOrderItem::class, 'shop_order_items', 'order_id', 'item_id')->withPivot(
            'quantity',
            'net_price',
            'discount_price',
            'price',
            'start_sum',
            'discount_sum',
            'result_sum'
        );
    }

    public function customer()
    {
        return $this->belongsTo(ShopCustomer::class, 'customer_id');
    }

    public function delivery()
    {
        return $this->belongsTo(ShopDelivery::class, 'delivery_id');
    }

    public function order_status()
    {
        return $this->belongsTo(ShopOrderStatus::class, 'order_status_id');
    }

    public function payment_method()
    {
        return $this->belongsTo(ShopPaymentMethod::class, 'payment_method_id');
    }

}
