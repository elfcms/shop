<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopVendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'number'
    ];

    public function products()
    {
        return $this->hasMany(ShopProduct::class, 'vendor_id');
    }

    public function items()
    {
        return $this->hasMany(ShopItem::class, 'vendor_id');
    }
}
