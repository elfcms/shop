<?php

namespace Elfcms\Shop\Models;

use Elfcms\Shop\Models\ShopProduct;
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

    public function products()
    {
        return $this->hasMany(ShopProduct::class, 'unit_id');
    }

    public function getTitleAttribute()
    {
        $title = $this->name;
        if (!empty($this->lang_name)) {
            $lang_title = __('shop::default.' . $this->lang_name);
            if ($lang_title != 'shop::default.' . $this->lang_name) {
                $title = $lang_title;
            }
        }
        return $title;
    }

}
