<?php

namespace Elfcms\Shop\Models;

use Elfcms\Elfcms\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'firstname',
        'lastname',
        'birthday',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(ShopOrder::class, 'customer_id');
    }

    public function invoices()
    {
        return $this->hasMany(ShopInvoice::class, 'customer_id');
    }

    public function cart()
    {
        return $this->hasOne(ShopCart::class, 'customer_id')->withCount('items')->with('items');
    }

    public function addresses()
    {
        return $this->hasMany(ShopCustomerAddress::class, 'customer_id');
    }

}
