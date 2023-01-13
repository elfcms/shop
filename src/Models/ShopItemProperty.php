<?php

namespace Elfcms\Shop\Models;

use Elfcms\Basic\Models\DataType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItemProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_type_id',
        'name',
        'code',
        'description',
        'is_filter',
    ];

    public function data_type()
    {
        return $this->belongsTo(DataType::class, 'data_type_id');
    }

    public function goods_values()
    {
        return $this->hasMany(ShopGoodsPropertyValue::class, 'property_id');
    }

    public function item_values()
    {
        return $this->hasMany(ShopItemPropertyValue::class, 'property_id');
    }
}
