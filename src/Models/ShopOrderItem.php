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

}
