<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'price_type_id',
        'value',
        'currency_id',
        'current',
        'old',
    ];

    public function getValueAttribute()
    {
        return $this->attributes['value'] / 100;
    }

    public function price_type()
    {
        return $this->belongsTo(ShopPriceType::class, 'price_type_id');
    }

    public function item()
    {
        return $this->belongsTo(ShopItem::class, 'item_id');
    }

    public function currency()
    {
        return $this->belongsTo(ShopCurrency::class, 'currency_id');
    }
}
