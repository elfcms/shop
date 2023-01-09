<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCurrency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'symbol',
        'symbol_code',
    ];

    public function price_types()
    {
        return $this->belongsToMany(ShopPriceType::class, 'shop_price_type_currencies', 'currency_id', 'price_type_id');
    }

    public function prices()
    {
        return $this->hasMany(ShopPrice::class, 'currency_id');
    }

    public function coupons()
    {
        return $this->hasMany(ShopCoupon::class, 'currency_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(ShopTax::class, 'shop_taxes_currencies', 'currency_id', 'tax_id');
    }
}
