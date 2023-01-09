<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrderStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_complited',
        'order',
    ];

    public function orders()
    {
        return $this->hasMany(ShopOrder::class, 'order_status_id');
    }
}
