<?php

namespace Elfcms\Shop\Models;

use Elfcms\Shop\Models\ShopGoods;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_plural',
        'abbr',
        'lang_name',
        'lang_name_plural',
        'step',
        'min',
        'max',
    ];

    public function goods()
    {
        return $this->hasMany(ShopGoods::class, 'unit_id');
    }

}
