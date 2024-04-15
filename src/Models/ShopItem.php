<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
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

    protected $appends = [
        'publicPrices'
    ];

    public function scopeActive($query)
    {
        return $query->where('active',1);
    }

    public function getPublicPricesAttribute()
    {
        $old = $this->prices()->where('old',1)->first();
        $current = $this->prices()->where('current',1)->first();
        $default = $this->prices()->first();

        $first = $current;
        $second = null;
        if (!empty($old)) {
            $first = $old;
            $second = $current;
        }

        return (object)[
            'old' => $old->value ?? null,
            'current' => $current->value ?? null,
            'default' => $default->value ?? null,
            'first' => $first,
            'second' => $second,
            'isOld' => !empty($second)
        ];
    }

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id');
    }

    public function vendor()
    {
        return $this->belongsTo(ShopVendor::class, 'vendor_id');
    }

    public function prices()
    {
        return $this->hasMany(ShopPrice::class, 'item_id')->with('currency');
    }

    /* public function oldPrice()
    {
        return $this->prices()->where('old',1);
    }

    public function currentPrice()
    {
        return $this->prices()->where('current',1);
    } */

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

    public function discounts ()
    {
        return $this->belongsToMany(ShopItem::class, 'shop_item_discounts', 'item_id', 'discount_id');
    }

}
