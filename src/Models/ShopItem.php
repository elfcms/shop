<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'goods_id',
        'item_number',
        'vendor_item_number',
        'image',
        'description',
        'additional_name',
        'alternate_name',
        'note',
        'vendor_id',
        'active',
        'meta_keywords',
        'meta_description',
    ];

    public function scopeActive($query)
    {
        return $query->where('active',1);
    }

    public function goods()
    {
        return $this->belongsTo(ShopGoods::class, 'goods_id');
    }

    public function vendor()
    {
        return $this->belongsTo(ShopVendor::class, 'vendor_id');
    }

    public function prices()
    {
        return $this->hasMany(ShopPrice::class, 'item_id');
    }

    public function carts()
    {
        return $this->belongsToMany(ShopCart::class, 'shop_cart_items', 'cart_id', 'item_id');
    }

    public function orders()
    {
        return $this->belongsToMany(ShopOrder::class, 'shop_order_items', 'order_id', 'item_id');
    }

    public function parameters()
    {
        return $this->hasMany(ShopItemParameterValue::class, 'item_id');
    }

    public function properties()
    {
        return $this->hasMany(ShopItemPropertyValue::class, 'item_id');
    }

    public function tags()
    {
        return $this->belongsToMany(ShopTag::class, 'shop_item_tags', 'item_id', 'tag_id');
    }

}
