<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'return_status_id',
    ];

    public function status()
    {
        return $this->belongsTo(ShopReturnStatus::class, 'return_status_id');
    }

    public function reason()
    {
        return $this->belongsTo(ShopReturnReason::class, 'reason_id');
    }

    public function order()
    {
        return $this->belongsTo(ShopOrder::class, 'order_id');
    }
}
