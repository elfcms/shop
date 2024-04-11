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
        'title',
    ];
}
