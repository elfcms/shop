<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopTax extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
    ];

    public function taxes()
    {
        return $this->belongsToMany(ShopCurrency::class, 'shop_taxes_currencies', 'currency_id', 'tax_id');
    }

    public function price_types ()
    {
        return $this->hasMany(ShopPriceType::class, 'tax_id');
    }
}
