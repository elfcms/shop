<?php

namespace Elfcms\Shop\Models;

use Elfcms\Basic\Models\DataType;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'multiple',
        'options',
    ];

    public $value, $colorData;

    public function data_type()
    {
        return $this->belongsTo(DataType::class, 'data_type_id');
    }

    public function setValueAttribute()
    {
        return null;
    }
/*
    public function product_values()
    {
        return $this->hasMany(ShopProductPropertyValue::class, 'property_id');
    }

    public function item_values()
    {
        return $this->hasMany(ShopItemPropertyValue::class, 'property_id');
    }
 */

    public function product_values(int|null $productId = null)
    {
        $result = $this->hasMany(ShopProductPropertyValue::class, 'property_id');
        if (!empty($productId) && is_numeric($productId)) {
            $result = $result->where('product_id', $productId)->get()->toArray();
            if (!empty($result)) {
                $result = $result[0][$this->data_type->code . '_value'];
            }
            else {
                $result = null;
            }
        }
        return $result;
    }

    public function item_values(int|null $itemId = null)
    {
        $result =  $this->hasMany(ShopItemPropertyValue::class, 'property_id');
        if (!empty($itemId) && is_numeric($itemId)) {
            $result = $result->where('item_id', $itemId)->get()->toArray();
            if (!empty($result)) {
                $result = $result[0][$this->data_type->code . '_value'];
            }
            else {
                $result = null;
            }
        }
        return $result;
    }

    /**
     * Get the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function options(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
        );
    }
}
