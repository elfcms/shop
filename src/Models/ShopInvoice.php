<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'invoice_status_id',
        'start_sum',
        'discount_sum',
        'result_sum',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
