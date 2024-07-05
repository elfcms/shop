<?php

namespace Elfcms\Shop\Models;

use Elfcms\Elfcms\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'customer_id',
        'payment_method_id',
        'delivery_id',
        'customer_address_id',
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

    public function getResultSumAttribute()
    {
        return $this->attributes['result_sum'] / 100;
    }

    public function getStartSumAttribute()
    {
        return $this->attributes['start_sum'] / 100;
    }

    public function getDiscountSumAttribute()
    {
        return $this->attributes['discount_sum'] / 100;
    }

    public function getClearSumAttribute()
    {
        return $this->attributes['clear_sum'] / 100;
    }

    public function getDeliverySumAttribute()
    {
        return $this->attributes['delivery_sum'] / 100;
    }

    public function getNetPriceAttribute()
    {
        return $this->pivot->net_price / 100;
    }

    public function invoices()
    {
        return $this->hasMany(ShopInvoice::class, 'order_id');
    }

    public function orderItems()
    {
        return $this->hasMany(ShopOrderItem::class, 'order_id');
    }

    public function items()
    {
        return $this->belongsToMany(ShopItem::class, 'shop_order_items', 'order_id', 'item_id')->withPivot(
            'quantity',
            'net_price',
            'discount_price',
            'price',
            'start_sum',
            'discount_sum',
            'result_sum'
        )->as('prices');
    }

    public function address()
    {
        return $this->belongsTo(ShopCustomerAddress::class, 'customer_address_id');
    }

    public function customer()
    {
        return $this->belongsTo(ShopCustomer::class, 'customer_id');
    }

    public function delivery()
    {
        return $this->belongsTo(ShopDelivery::class, 'delivery_id');
    }

    public function status()
    {
        return $this->belongsTo(ShopOrderStatus::class, 'order_status_id');
    }

    public function payment_method()
    {
        return $this->belongsTo(ShopPaymentMethod::class, 'payment_method_id');
    }

    public function bills()
    {
        return $this->hasMany(ShopBill::class, 'order_id');
    }
}
