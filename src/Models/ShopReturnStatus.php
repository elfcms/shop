<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopReturnStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_complited',
        'order',
    ];

    public function returns()
    {
        return $this->hasMany(ShopOrder::class, 'return_status_id');
    }
}
