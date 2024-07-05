<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'customer_data',
        'number',
        'bill_date',
        'deadline',
        'strings',
        'sum',
        'discount',
        'total',
        'tax',
        'is_paid',
        'is_cancelled',
    ];

    public function order()
    {
        return $this->belongsTo(ShopOrder::class, 'order_id');
    }
}
