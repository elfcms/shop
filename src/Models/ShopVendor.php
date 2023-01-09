<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopVendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function goods()
    {
        return $this->hasMany(ShopGoods::class, 'vendor_id');
    }

    public function items()
    {
        return $this->hasMany(ShopItem::class, 'vendor_id');
    }
}
