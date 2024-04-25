<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopItemPicture extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_id',
        'image',
        'preview',
        'thumbnail',
        'description',
        'additional_text',
        'active',
        'position',
    ];

    public function scopeActive($query)
    {
        return $query->where('active',1);
    }

    public function item()
    {
        return $this->belongsTo(ShopItem::class);
    }
}
