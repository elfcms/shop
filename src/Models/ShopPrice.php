<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopPrice extends Model
{
    use HasFactory;

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
