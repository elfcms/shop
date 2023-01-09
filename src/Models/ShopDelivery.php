<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'icon',
    ];

    public function orders()
    {
        return $this->hasMany(ShopOrder::class, 'delivery_id');
    }

}
