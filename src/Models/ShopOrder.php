<?php

namespace Elfcms\Shop\Models;

use Elfcms\Elfcms\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_id',
        'delivery_id',
        'order_status_id',
        'start_sum',
        'discount_sum',
        'result_sum',
    ];

    public function invoces()
    {
        return $this->hasMany(ShopInvoice::class, 'order_id');
    }

    public function items()
    {
        return $this->belongsToMany(ShopInvoice::class, 'shop_order_items', 'order_id', 'item_id')->withPivot('quantity','price','start_sum','discount_sum','result_sum');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function delivery()
    {
        return $this->belongsTo(ShopDelivery::class, 'delivery_id');
    }

    public function order_status()
    {
        return $this->belongsTo(ShopOrderStatus::class, 'order_status_id');
    }

    public function payment()
    {
        return $this->belongsTo(ShopPayment::class, 'payment_id');
    }

}
