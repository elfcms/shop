<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopFavorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'item_id',
        'tmp_user_id',
        'title',
    ];

    public function item()
    {
        return $this->belongsTo(ShopItem::class, 'item_id');
    }
}
