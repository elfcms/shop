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

    public function item()
    {
        return $this->belongsTo(ShopItem::class, 'item_id');
    }

    public function cart()
    {
        return $this->belongsTo(ShopCart::class, 'cart_id');
    }

}
