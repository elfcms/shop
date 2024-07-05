<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'order_id',
        'customer_id',
        'invoice_status_id',
        'start_sum',
        'discount_sum',
        'clear_sum',
        'delivery_sum',
        'result_sum',
        'deadline',
        'file',
    ];

    public function customer()
    {
        return $this->belongsTo(ShopCustomer::class);
    }

    public function status()
    {
        return $this->belongsTo(ShopInvoiceStatus::class, 'invoice_status_id');
    }

    public function order()
    {
        return $this->belongsTo(ShopOrder::class, 'order_id');
    }

}
