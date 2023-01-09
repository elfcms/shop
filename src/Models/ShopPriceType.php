<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopPriceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tax_id',
    ];

    public function prices()
    {
        return $this->hasMany(ShopPrice::class, 'price_type_id');
    }

    public function tax()
    {
        return $this->belongsTo(ShopTax::class, 'tax_id');
    }

    public function currencies()
    {
        return $this->belongsToMany(ShopCurrency::class, 'shop_price_type_currencies', 'currency_id', 'price_type_id');
    }
}
