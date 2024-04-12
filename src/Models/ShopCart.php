<?php

namespace Elfcms\Shop\Models;

use Elfcms\Elfcms\Models\TmpUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'tmp_user_id'
    ];

    public function customer()
    {
        return $this->belongsTo(ShopCustomer::class, 'customer_id');
    }

    public function tmp_user()
    {
        return $this->belongsTo(TmpUser::class, 'tmp_user_id');
    }

    public function items()
    {
        return $this->belongsToMany(ShopItem::class, 'shop_cart_items', 'item_id', 'cart_id');
    }

    public function cartItems()
    {
        return $this->hasMany(ShopCartItem::class, 'cart_id');
    }
}
