<?php

namespace Elfcms\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopTestimonialLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'testimonial_id',
        'customer_id',
        'tmp_user',
        'useful',
        'useless'
    ];

    public function testimonial()
    {
        return $this->belongsTo(ShopTestimonial::class, 'item_id');
    }

    public function customer()
    {
        return $this->belongsTo(ShopCustomer::class, 'customer_id');
    }
}
