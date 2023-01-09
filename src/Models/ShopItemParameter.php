<?php

namespace Elfcms\Shop\Models;

use Elfcms\Basic\Models\DataType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItemParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_type_id',
        'name',
        'code',
        'description',
    ];

    public function data_type()
    {
        return $this->belongsTo(DataType::class, 'data_type_id');
    }

    public function goods_values()
    {
        return $this->hasMany(ShopGoodsParameterValue::class, 'parameter_id');
    }

    public function item_values()
    {
        return $this->hasMany(ShopItemParameterValue::class, 'parameter_id');
    }
}
