<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopGoods extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'vendor_id',
        'brand_id',
        'active',
        'meta_keywords',
        'meta_description',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('active',1);
    }

    public function categories()
    {
        return $this->belongsToMany(ShopCategory::class, 'shop_goods_categories', 'goods_id', 'category_id')->withPivot('is_main');
    }

    public function category()
    {
        return $this->categories()->wherePivot('is_main',1);
    }

    public function items()
    {
        return $this->hasMany(ShopItem::class, 'goods_id');
    }

    public function unit()
    {
        return $this->belongsTo(ShopUnit::class, 'unit_id');
    }

    public function brand()
    {
        return $this->belongsTo(ShopBrand::class, 'brand_id');
    }

    public function vendor()
    {
        return $this->belongsTo(ShopVendor::class, 'vendor_id');
    }

    public function parameters()
    {
        return $this->hasMany(ShopGoodsParameterValue::class, 'goods_id');
    }

    public function properties()
    {
        return $this->hasMany(ShopGoodsPropertyValue::class, 'goods_id');
    }

    public function tags()
    {
        return $this->belongsToMany(ShopTag::class, 'shop_goods_tags', 'goods_id', 'tag_id');
    }

}
