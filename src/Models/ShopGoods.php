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
        'vendor_id',
        'brand_id',
    ];

    public function categories()
    {
        return $this->belongsToMany(ShopCategory::class, 'shop_goods_categories', 'goods_id', 'category_id')->withPivot('is_main');
    }

    public function category()
    {
        return $this->categories()->wherePivot('is_main',1);
    }

    public function brand()
    {
        return $this->belongsTo(ShopBrand::class, 'brand_id');
    }

    public function vendor()
    {
        return $this->belongsTo(ShopVendor::class, 'vendor_id');
    }

}
