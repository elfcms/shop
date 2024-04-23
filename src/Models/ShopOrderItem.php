<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrderItem extends Model
{
    use HasFactory;

    protected $table = 'shop_order_items';

    protected $fillable = [
        'order_id',
        'item_id',
        'quantity',
        'net_price',
        'discount_price',
        'price',
        'start_sum',
        'discount_sum',
        'result_sum'
    ];

    public function getNetPriceAttribute()
    {
        return $this->attributes['net_price'] / 100;
    }

    public function getDiscountPriceAttribute()
    {
        return $this->attributes['discount_price'] / 100;
    }

    public function getPriceAttribute()
    {
        return $this->attributes['price'] / 100;
    }

    public function getStartSumAttribute()
    {
        return $this->attributes['start_sum'] / 100;
    }

    public function getDiscountSumAttribute()
    {
        return $this->attributes['discount_sum'] / 100;
    }

    public function getResultSumAttribute()
    {
        return $this->attributes['result_sum'] / 100;
    }

}
