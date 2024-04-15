<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value'
    ];

    public function items ()
    {
        return $this->belongsToMany(ShopItem::class, 'shop_item_discounts', 'item_id', 'discount_id');
    }
}
