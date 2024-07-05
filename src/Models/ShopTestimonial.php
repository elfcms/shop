<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopTestimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'item_id',
        'text',
        'rating',
        'useful',
        'useless'
    ];

    protected $appends = [
        'usefulCount',
        'uselessCount',
    ];

    public function getUsefulCountAttribute() {
        return $this->likes()->where('useful',1)->get()->count();
    }

    public function getUselessCountAttribute() {
        return $this->likes()->where('useless',1)->get()->count();
    }

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id');
    }

    public function item()
    {
        return $this->belongsTo(ShopItem::class, 'item_id');
    }

    public function customer()
    {
        return $this->belongsTo(ShopCustomer::class, 'customer_id');
    }

    /* public function usefuls() {
        return $this->hasMany(ShopTestimonialLike::class, 'testimonial_id');
    }

    public function uselesses() {
        return $this->hasMany(ShopTestimonialLike::class, 'testimonial_id');
    } */

    public function likes()
    {
        return $this->hasMany(ShopTestimonialLike::class, 'testimonial_id');
    }
}
