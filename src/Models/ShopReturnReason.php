<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopReturnReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function returns()
    {
        return $this->hasMany(ShopReturn::class, 'reason_id');
    }
}
