<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag'
    ];

    public function items()
    {
        return $this->belongsToMany(ShopItem::class, 'shop_item_tags', 'item_id', 'tag_id');
    }

    public function products()
    {
        return $this->belongsToMany(ShopProduct::class, 'shop_item_tags', 'product_id', 'tag_id');
    }

}
