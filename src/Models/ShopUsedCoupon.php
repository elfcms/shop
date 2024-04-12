<?php

namespace Elfcms\Shop\Models;

use Elfcms\Shop\Models\ShopCoupon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopUsedCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id',
        'customer_id',
        'code'
    ];

    public function coupon()
    {
        return $this->belongsTo(ShopCoupon::class, 'coupon_id');
    }
}
