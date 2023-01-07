<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'image',
        'company',
        'description',
    ];

    public function goods ()
    {
        return $this->hasMany(ShopBrand::class, 'brand_id');
    }
}
