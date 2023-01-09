<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopInvoiceStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_complited',
        'order',
    ];

    public function invoces()
    {
        return $this->hasMany(ShopInvoice::class, 'invoice_status_id');
    }
}
