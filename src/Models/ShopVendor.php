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

    public function goods ()
    {
        return $this->hasMany(ShopVendor::class, 'vendor_id');
    }
}
