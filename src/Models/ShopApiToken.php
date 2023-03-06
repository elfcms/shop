<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopApiToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'token'
    ];
}
