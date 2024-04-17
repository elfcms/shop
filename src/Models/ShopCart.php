<?php

namespace Elfcms\Shop\Models;

use Elfcms\Elfcms\Models\TmpUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'tmp_user_id'
    ];

    public function getTotalAttribute() {
        $netSum = 0;
        $clearSum = 0;
        $discountSum = 0;
        $itemQuantity = 0;
        $totalQuantity = 0;
        foreach ($this->items as $item) {
            $netSum += $item->netPrice * $item->pivot->quantity;
            $clearSum += $item->price * $item->pivot->quantity;
            /* if ($item->id == 1) {
                dd($item->discountSum);
            } */
            $discountSum += ($item->discountSum ?? 0) * $item->pivot->quantity;
        }

        $itemQuantity = $this->items()->count();

        $totalQuantity = (int)$this->items()->sum('quantity');

        return (object)[
            'netSum' => $netSum,
            'discountSum' => $discountSum,
            'clearSum' => $clearSum,
            'itemQuantity' => $itemQuantity,
            'totalQuantity' => $totalQuantity,
        ];
    }

    public function customer()
    {
        return $this->belongsTo(ShopCustomer::class, 'customer_id');
    }

    public function tmp_user()
    {
        return $this->belongsTo(TmpUser::class, 'tmp_user_id');
    }

    public function items()
    {
        return $this->belongsToMany(ShopItem::class, 'shop_cart_items', 'cart_id', 'item_id')->withPivot('quantity');
    }

    public function cartItems()
    {
        return $this->hasMany(ShopCartItem::class, 'cart_id');
    }
}
