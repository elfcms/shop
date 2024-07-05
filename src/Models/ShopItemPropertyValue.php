<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItemPropertyValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'item_id',
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

    public function property()
    {
        return $this->belongsTo(ShopItemProperty::class, 'property_id');
    }

    public function item()
    {
        return $this->belongsTo(ShopItem::class, 'item_id');
    }

    public function color()
    {
        return $this->belongsTo(ShopColor::class, 'color_value');
    }

    public function getValueAttribute()
    {
        $field = $this->property->data_type->code . '_value';
        if ($this->property->data_type->code == 'color') {
            return [
                'id' => $this->color->id ?? null,
                'code' => $this->color->code ?? null,
                'name' => $this->color->name ?? null,
                'picture' => $this->color->picture ?? null,
                'transparent' => $this->color->transparent ?? null
            ];
        }
        return $this->{$field};
    }

    public function getCodeAttribute()
    {
        return $this->property->code;
    }

    public function getNameAttribute()
    {
        return $this->property->name;
    }

    public function getTypeCodeAttribute()
    {
        return $this->property->data_type->code;
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
