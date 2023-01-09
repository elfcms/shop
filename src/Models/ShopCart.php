<?php

namespace Elfcms\Shop\Models;

use Elfcms\Basic\Models\TmpUser;
use Elfcms\Basic\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tmp_user_uuid'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tmp_user()
    {
        return $this->belongsTo(TmpUser::class, 'tmp_user_id');
    }

    public function items()
    {
        return $this->belongsToMany(ShopItem::class, 'shop_cart_items', 'item_id', 'cart_id');
    }
}
