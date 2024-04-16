<?php

namespace Elfcms\Shop\Http\Controllers\Sellings;

use Elfcms\Shop\Models\ShopFavorite;
use Elfcms\Shop\Models\ShopItem;

class CustomerController extends SellingController
{

    public function setFavorite(ShopItem $item)
    {
        $this->startResult();
        return ShopFavorite::firstOrCreate(
            [
                'customer_id' => $this->customer?->id,
                'item_id' => $item->id,
                'tmp_user_id' => $this->tmp_user?->id,
            ],
            ['title' => $item->alternate_name ?? trim($item->product->title . ' ' . $item->additional_name)]
        );
    }

    public function unsetFavorite(null|ShopFavorite $favorite)
    {
        $this->startResult();
        if (!$favorite) {
            $this->message = 'Product item not found';
            $this->error = true;
            return false;
        }

        if ($favorite->customer_id != $this->customer?->id && $favorite->tmp_user_id != $this->tmp_user?->id) {
            $this->message = 'User not found';
            $this->error = true;
            return false;
        }
        return $favorite->delete();
    }

    public function unsetFavoriteItem(null|ShopItem $item)
    {
        $this->startResult();
        if (!$item) {
            $this->message = 'Product item not found';
            $this->error = true;
            return false;
        }

        if ($this->customer && $this->customer->id) {
            $favorite = ShopFavorite::where('customer_id', $this->customer->id)->where('item_id', $item->id)->first();
        } elseif ($this->tmp_user && $this->tmp_user->id) {
            $favorite = ShopFavorite::where('tmp_user_id', $this->tmp_user->id)->where('item_id', $item->id)->first();
        }

        if (!$favorite) {
            $this->message = 'Product item not found';
            $this->error = true;
            return false;
        }

        return $favorite->delete();
    }

    public function addToCart(ShopItem $item, $quantity = 1)
    {
        if (!$this->cart) {
            $this->setCart();
        }
        $cartItem = $this->cart->cartItems()->where('item_id', $item->id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            $cartItem = $this->cart->cartItems()->create([
                'item_id' => $item->id,
                'quantity' => $quantity
            ]);
        }
        return $cartItem;
    }

    public function removeFromCart(ShopItem $item, $quantity = 0)
    {
        if (!$this->cart) {
            $this->message = 'Cart not found';
            $this->error = true;
            return false;
        }
        $cartItem = $this->cart->cartItems()->where('item_id', $item->id)->first();
        if (!$cartItem) {
            $this->message = 'Product item not found in cart';
            $this->error = true;
            return false;
        }
        if (empty($quantity) || $cartItem->quantity <= $quantity) {
            $cartItem->delete();
            $this->message = 'Product item removed succesfully from cart';
            $this->error = false;
            return true;
        }
        else {
            $cartItem->decrement('quantity', $quantity);
        }
        return $cartItem;
    }

    public function clearCart() {
        if (!$this->cart) {
            $this->message = 'Cart not found';
            $this->error = true;
            return false;
        }
        $this->cart->cartItems()->delete();
        $this->message = 'Cart cleared succesfully';
        $this->error = false;
        return true;
    }

    public function useCoupon($code, $check = false)
    {
        if (!$this->customer || !$this->customer->id) {
            $this->message = 'User not found';
            $this->error = true;
            return false;
        }
        return CouponController::useCoupon($code, $this->customer->id, $check);
    }

    public function setOrder() {
        if (!$this->cart) {
            $this->message = 'Cart not found';
            $this->error = true;
            return false;
        }

    }
}
