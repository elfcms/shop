<?php

namespace Elfcms\Shop\Http\Controllers\Sellings;

use Elfcms\Shop\Models\ShopDelivery;
use Elfcms\Shop\Models\ShopFavorite;
use Elfcms\Shop\Models\ShopItem;
use Elfcms\Shop\Models\ShopOrder;
use Elfcms\Shop\Models\ShopOrderItem;
use Elfcms\Shop\Models\ShopOrderStatus;
use Elfcms\Shop\Models\ShopPaymentMethod;

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

    public function setOrder(int|ShopPaymentMethod $pamentMethod, int|ShopDelivery $delivery, $deliverySum = 0) {
        if (!$this->cart) {
            $this->message = 'Cart not found';
            $this->error = true;
            return false;
        }
        if (!$this->isAuth) {
            $this->message = 'You are not authorized';
            $this->error = true;
            return false;
        }
        if (!$this->customer) {
            $this->message = 'User not found';
            $this->error = true;
            return false;
        }

        $pamentMethodId = is_numeric($pamentMethod) ? $pamentMethod : ($pamentMethod->id ?? null);
        if (!$pamentMethodId) {
            $this->message = 'Payment method is not defined';
            $this->error = true;
            return false;
        }

        $deliveryId = is_numeric($delivery) ? $delivery : ($delivery->id ?? null);
        if (!$deliveryId) {
            $this->message = 'Delivery service is not defined';
            $this->error = true;
            return false;
        }

        if (empty($this->cart->total->itemQuantity) || empty($this->cart->total->totalQuantity)) {
            $this->message = 'There are no products to order';
            $this->error = true;
            return false;
        }

        $startStatus = ShopOrderStatus::where('is_start', 1)->first();
        if (!$startStatus) {
            $startStatus = ShopOrderStatus::where('code', 'N')->first();
        }
        if (!$startStatus) {
            $startStatus = ShopOrderStatus::first();
        }
        if (!$startStatus) {
            $startStatus = ShopOrderStatus::create([
                'code'=>'N',
                'name'=>'New',
                'is_start'=>1
            ]);
        }

        if (empty($deliverySum) && !empty($delivery->price)) {
            $deliverySum = $delivery->price;
        }

        $order = ShopOrder::create([
            'customer_id' => $this->customer->id,
            'payment_method_id' => $pamentMethodId,
            'delivery_id' => $deliveryId,
            'order_status_id' => $startStatus->id,
            'start_sum' => $this->cart->total->netSum * 100,
            'discount_sum' => $this->cart->total->discountSum * 100,
            'clear_sum' => $this->cart->total->clearSum * 100,
            'delivery_sum' => $deliverySum * 100,
            'result_sum' => ($this->cart->total->clearSum + (int)$deliverySum) * 100,
            'item_quantity' => $this->cart->total->itemQuantity,
            'total_quantity' => $this->cart->total->totalQuantity,
        ]);

        if (!$order) {
            $this->message = 'Order not created';
            $this->error = true;
            return false;
        }

        foreach ($this->cart->items as $item) {
            ShopOrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item->id,
                'quantity' => $item->pivot->quantity,
                'net_price' => $item->netPrice * 100,
                'discount_price' => $item->discountSum * 100,
                'price' => $item->price * 100,
                'start_sum' => $item->netPrice * $item->pivot->quantity * 100,
                'discount_sum' => $item->pivot->quantity * $item->discountSum * 100,
                'result_sum' => $item->price * $item->pivot->quantity * 100,
            ]);
        }
        return $order;
    }
}
