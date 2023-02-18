<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopProductParameterValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'parameter_id',
        'product_id',
        'bool_value',
        'int_value',
        'float_value',
        'string_value',
        'text_value',
        'date_value',
        'datetime_value',
        'file_value',
        'image_value',
        'list_value',
        'json_value',
        'time_value',
        'color_value',
    ];

    public function parameter()
    {
        return $this->belongsTo(ShopItemParameter::class, 'parameter_id');
    }

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id');
    }

    public function getValueAttribute()
    {
        $field = $this->parameter->data_type->code . '_value';
        return $this->{$field};
    }

    public function getCodeAttribute()
    {
        return $this->parameter->code;
    }

    public function getNameAttribute()
    {
        return $this->parameter->name;
    }

    public function getTypeCodeAttribute()
    {
        return $this->parameter->data_type->code;
    }

    /**
     * Get the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function listValue(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
        );
    }
}
