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

    public function goods()
    {
        return $this->belongsToMany(ShopGoods::class, 'shop_item_tags', 'goods_id', 'tag_id');
    }

}
