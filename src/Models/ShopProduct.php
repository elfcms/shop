<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_number',
        'title',
        'slug',
        'image',
        'description',
        'vendor_id',
        'brand_id',
        'unit_id',
        'active',
        'meta_keywords',
        'meta_description',
    ];

    protected $appends = [
        'rating',
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

    public function getRatingAttribute() {
        $testimonials = $this->testimonials()->pluck('rating')->toArray() ?? [];
        if(count($testimonials) > 0) {
            return round(array_sum($testimonials) / count($testimonials), 1);
        }
        return null;
    }

    public function categories()
    {
        return $this->belongsToMany(ShopCategory::class, 'shop_product_categories', 'product_id', 'category_id')->withPivot('is_main');
    }

    public function category()
    {
        return $this->categories()->wherePivot('is_main',1);
    }

    public function items()
    {
        return $this->hasMany(ShopItem::class, 'product_id')->with('prices');
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
        return $this->hasMany(ShopProductParameterValue::class, 'product_id');
    }

    public function properties()
    {
        return $this->hasMany(ShopProductPropertyValue::class, 'product_id');
    }

    public function tags()
    {
        return $this->belongsToMany(ShopTag::class, 'shop_product_tags', 'product_id', 'tag_id');
    }

    public function testimonials()
    {
        return $this->hasMany(ShopTestimonial::class, 'product_id');
    }
}
