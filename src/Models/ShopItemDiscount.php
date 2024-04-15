<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItemDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'discount_id'
    ];
}
