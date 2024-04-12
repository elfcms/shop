<?php

namespace Elfcms\Shop\Models;

use Elfcms\Elfcms\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'value',
        'is_percent',
        'min_price',
        'max_price',
        'min_value',
        'min_is_percent',
        'max_value',
        'max_is_percent',
        'uses_number',
        'currency_id',
        'valid_from',
        'valid_until',
        'customer_id',
        'combine',
    ];

    public function currency()
    {
        return $this->belongsTo(ShopCurrency::class, 'currency_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function used()
    {
        return $this->hasMany(ShopUsedCoupon::class, 'coupon_id');
    }
}
