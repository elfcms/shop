<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCartItem extends Model
{
    use HasFactory;

    protected $table = 'shop_cart_items';

    protected $fillable = [
        'cart_id',
        'item_id',
        'quantity'
    ];

}
