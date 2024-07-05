<?php

namespace Elfcms\Shop\Http\Controllers\Sellings;

use Elfcms\Shop\Models\ShopCustomerAddress;
use Elfcms\Shop\Models\ShopDelivery;
use Elfcms\Shop\Models\ShopFavorite;
use Elfcms\Shop\Models\ShopItem;
use Elfcms\Shop\Models\ShopOrder;
use Elfcms\Shop\Models\ShopOrderItem;
use Elfcms\Shop\Models\ShopOrderStatus;
use Elfcms\Shop\Models\ShopPaymentMethod;
use Elfcms\Shop\Models\ShopProduct;
use Elfcms\Shop\Models\ShopReturn;
use Elfcms\Shop\Models\ShopReturnStatus;
use Elfcms\Shop\Models\ShopTestimonial;
use Elfcms\Shop\Models\ShopTestimonialLike;

class CustomerController extends SellingController
{
    /* public $favorites;

    public function __construct()
    {
        parent::__construct();
        $this->favorites = ShopFavorite::where('customer_id', $this->customer->id)->get() ?? null;
    } */

    public function isFavorite(ShopItem $item)
    {
        $this->startResult();
        $result = false;
        if ($this->customer && !empty($this->customer->id)) {
            $result = ShopFavorite::where('customer_id', $this->customer->id)->where('item_id', $item->id)->first() ?? false;
        } elseif ($this->tmp_user  && !empty($this->tmp_user->id)) {
            $result = ShopFavorite::where('tmp_user_id', $this->tmp_user->id)->where('item_id', $item->id)->first() ?? false;
        }
        return $result;
    }

    public function getFavorites()
    {
        $this->startResult();
        return ShopFavorite::where('customer_id', $this->customer->id)->get() ?? null;
    }

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
            $this->message = __('shop::default.product_item_not_found');
            $this->error = true;
            return false;
        }

        if ($favorite->customer_id != $this->customer?->id && $favorite->tmp_user_id != $this->tmp_user?->id) {
            $this->message = __('elfcms::default.user_not_found');
            $this->error = true;
            return false;
        }
        return $favorite->delete();
    }

    public function unsetFavoriteItem(null|ShopItem $item)
    {
        $this->startResult();
        if (!$item) {
            $this->message = __('shop::default.product_item_not_found');
            $this->error = true;
            return false;
        }

        if ($this->customer && $this->customer->id) {
            $favorite = ShopFavorite::where('customer_id', $this->customer->id)->where('item_id', $item->id)->first();
        } elseif ($this->tmp_user && $this->tmp_user->id) {
            $favorite = ShopFavorite::where('tmp_user_id', $this->tmp_user->id)->where('item_id', $item->id)->first();
        }

        if (!$favorite) {
            $this->message = __('shop::default.product_item_not_found');
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

        if (empty($item->price)) {
            $this->message = __('shop::default.not_enough_quantity');
            $this->error = true;
            return false;
        }
        if ($cartItem) {
            if ($cartItem->quantity + $quantity > $item->balance) {
                $this->message = __('shop::default.not_enough_quantity');
                $this->error = true;
                return false;
            }
            $cartItem->increment('quantity', $quantity);
        } else {
            if ($quantity > $item->balance) {
                $this->message = __('shop::default.not_enough_quantity');
                $this->error = true;
                return false;
            }
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
            $this->message = __('shop::default.cart_not_found');
            $this->error = true;
            return false;
        }
        $cartItem = $this->cart->cartItems()->where('item_id', $item->id)->first();
        if (!$cartItem) {
            $this->message = __('shop::default.product_item_not_found_in_cart');
            $this->error = true;
            return false;
        }
        if (empty($quantity) || $cartItem->quantity <= $quantity) {
            $cartItem->delete();
            $this->message = __('shop::default.product_item_removed_successfully_from_cart');
            $this->error = false;
            return true;
        } else {
            $cartItem->decrement('quantity', $quantity);
        }
        return $cartItem;
    }

    public function clearCart()
    {
        if (!$this->cart) {
            $this->message = __('shop::default.cart_not_found');
            $this->error = true;
            return false;
        }
        $this->cart->cartItems()->delete();
        $this->message = __('shop::default.cart_cleared_succesfully');
        $this->error = false;
        return true;
    }

    public function useCoupon($code, $check = false)
    {
        if (!$this->customer || !$this->customer->id) {
            $this->message = __('elfcms::default.user_not_found');
            $this->error = true;
            return false;
        }
        return CouponController::useCoupon($code, $this->customer->id, $check);
    }

    public function setAddress(array|object $address)
    {
        if (!$this->customer) {
            $this->message = __('elfcms::default.user_not_found');
            $this->error = true;
            return false;
        }

        $address['customer_id'] = $this->customer->id;

        if (is_object($address)) {
            (array)$address;
        }
        if (empty($address['plz'])) {
            $this->message = __('shop::default.ZIP_must_be_not_empty');
            $this->error = true;
            return false;
        }
        if (empty($address['country'])) {
            $this->message = __('shop::default.country_must_be_not_empty');
            $this->error = true;
            return false;
        }
        if (empty($address['city'])) {
            $this->message = __('shop::default.city_must_be_not_empty');
            $this->error = true;
            return false;
        }
        if ((empty($address['street']) || empty($address['house'])) && empty($address['address'])) {
            $this->message = __('shop::default.address_must_be_not_empty');
            $this->error = true;
            return false;
        }
        $data = array_filter(
            $address,
            function ($key) {
                return in_array($key, ['plz', 'country', 'region', 'city', 'street', 'house', 'flat', 'address', 'additional', 'geodata_zip_id', 'geodata_country_id', 'geodata_region_id', 'geodata_city_id', 'geodata_street_id']);
            },
            ARRAY_FILTER_USE_KEY
        );

        $cid = $this->customer->id;

        $result = ShopCustomerAddress::updateOrCreate(['customer_id' => $cid], $data);
        if (!$result || empty($result->id)) {
            $this->message = __('shop::default.address_was_not_added');
            $this->error = true;
            return false;
        }
        if ($result->wasRecentlyCreated) {
            $this->message = __('shop::default.address_was_created');
            $this->error = false;
        } else {
            $this->message = __('shop::default.address_was_found');
            $this->error = false;
        }

        return $result;
    }

    /* public function orderNumber(int|ShopOrder $order) {
        if (is_int($order)) {
            $order = ShopOrder::find($order);
        }
        if (!empty($order->number)) {
            return $order->number;
        }
        $number = $order->id;
        if ($number < 10) {
            $number = '000'.$number;
        }
        elseif ($number < 100) {
            $number = '00'.$number;
        }
        elseif ($number < 1000) {
            $number = '0'.$number;
        }

        $date = $order->created_at->format('Ym') ?? date('Ym');

        $control = array_sum(str_split($date . $number));

        while ($control >= 10) {
            $control = array_sum(str_split($control));
        }

        $number = $date . $control . $number;
        $order->number = $number;
        $order->update();

        return $number;
    } */

    public function setOrder(int|ShopPaymentMethod $paymentMethod, int|array|ShopCustomerAddress $address, int|ShopDelivery $delivery, $deliverySum = 0)
    {
        if (!$this->cart) {
            $this->message = __('shop::default.cart_not_found');
            $this->error = true;
            return false;
        }
        if (!$this->isAuth) {
            $this->message = __('shop::default.you_are_not_authorized');
            $this->error = true;
            return false;
        }
        if (!$this->customer) {
            $this->message = __('elfcms::default.user_not_found');
            $this->error = true;
            return false;
        }

        $paymentMethodId = is_numeric($paymentMethod) ? $paymentMethod : ($paymentMethod->id ?? null);
        if (!$paymentMethodId) {
            $this->message = __('shop::default.payment_method_is_notdefined');
            $this->error = true;
            return false;
        }

        $deliveryId = is_numeric($delivery) ? $delivery : ($delivery->id ?? null);
        if (!$deliveryId) {
            $this->message = __('shop::default.delivery_service_is_not_defined');
            $this->error = true;
            return false;
        }

        if (empty($this->cart->items->count()) || empty($this->cart->total->itemQuantity) || empty($this->cart->total->totalQuantity)) {
            $this->message = __('shop::default.there_are_no_products_to_order');
            $this->error = true;
            return false;
        }

        if (is_numeric($address)) {
            $address = ShopCustomerAddress::find($address);
        }
        if (is_array($address)) {
            $address = $this->setAddress($address);
        }
        if (is_object($address) && $address instanceof ShopCustomerAddress) {
            if ($address->customer_id != $this->customer->id) {
                $this->message = __('shop::default.address_is_not_defined');
                $this->error = true;
                return false;
            }
        }
        if (!$address || empty($address->id)) {
            $this->message = __('shop::default.address_is_not_defined');
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
                'code' => 'N',
                'name' => __('shop::default.orderregistered'),
                'is_start' => 1
            ]);
        }

        if (empty($deliverySum) && !empty($delivery->price)) {
            $deliverySum = $delivery->price;
        }

        $order = ShopOrder::create([
            'customer_id' => $this->customer->id,
            'payment_method_id' => $paymentMethodId,
            'delivery_id' => $deliveryId,
            'customer_address_id' => $address->id,
            'order_status_id' => $startStatus->id,
            'start_sum' => $this->cart->total->netSum * 100,
            'discount_sum' => $this->cart->total->discountSum * 100,
            'clear_sum' => $this->cart->total->clearSum * 100,
            'delivery_sum' => $deliverySum * 100,
            'result_sum' => ($this->cart->total->clearSum + (float)$deliverySum) * 100,
            'item_quantity' => $this->cart->total->itemQuantity,
            'total_quantity' => $this->cart->total->totalQuantity,
        ]);

        if (!$order) {
            $this->message = __('shop::default.order_not_created');
            $this->error = true;
            return false;
        }

        //$this->orderNumber($order);

        foreach ($this->cart->items as $item) {
            if ($item->balance <=0) {
                continue;
            }
            $newItem = ShopOrderItem::create([
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
            if (!$newItem) {
                $this->message = __('shop::default.order_item_not_created');
                $this->error = true;
                $order->delete();
                return false;
            }
            $item->decrement('balance', $item->pivot->quantity);
        }

        $this->clearCart();
        $this->message = __('shop::default.order_created');
        $this->error = false;

        return $order;
    }

    public function setOrderReturn(int|ShopOrder $order)
    {
        if (!$this->customer) {
            $this->message = __('elfcms::default.user_not_found');
            $this->error = true;
            return false;
        }
        if (is_numeric($order)) {
            $order = ShopOrder::find($order);
        }
        if (!$order || empty($order->id)) {
            $this->message = __('shop::default.order_not_found');
            $this->error = true;
            return false;
        }

        if ($order->customer_id != $this->customer->id) {
            $this->message = __('shop::default.order_of_user_not_found');
            $this->error = true;
            return false;
        }

        if ($order->status->code != 'D') {
            $this->message = __('shop::default.order_can_not_be_returned');
            $this->error = true;
            return false;
        }

        $startStatus = ShopReturnStatus::where('code', 'N')->first();
        if (!$startStatus) {
            $startStatus = ShopReturnStatus::first();
        }
        if (!$startStatus) {
            $startStatus = ShopReturnStatus::create([
                'code' => 'N',
                'name' => __('shop::default.returnregistered'),
            ]);
        }

        $return = ShopReturn::create([
            'order_id' => $order->id,
            'return_status_id' => $startStatus->id,
        ]);

        if (!$return || empty($return->id)) {
            $this->message = __('shop::default.return_not_created');
            $this->error = true;
            return false;
        }

        $returnStatus = ShopOrderStatus::where('code', 'R')->first();
        if (!$returnStatus) {
            $returnStatus = ShopOrderStatus::create([
                'code' => 'R',
                'name' => __('shop::default.return'),
            ]);
        }

        $order->order_status_id = $returnStatus->id;
        $order->save();

        $this->message = __('shop::default.return_created');
        $this->error = false;
        return $return;
    }

    public function setOrderCancel(int|ShopOrder $order)
    {
        if (!$this->customer) {
            $this->message = __('elfcms::default.user_not_found');
            $this->error = true;
            return false;
        }
        if (is_numeric($order)) {
            $order = ShopOrder::find($order);
        }
        if (!$order || empty($order->id)) {
            $this->message = __('shop::default.order_not_found');
            $this->error = true;
            return false;
        }

        if ($order->customer_id != $this->customer->id) {
            $this->message = __('shop::default.order_of_user_not_found');
            $this->error = true;
            return false;
        }

        if (!in_array($order->status->code, ['N', 'W'])) {
            $this->message = __('shop::default.order_can_not_be_canceled');
            $this->error = true;
            return false;
        }

        $cancelStatus = ShopOrderStatus::where('code', 'C')->first();
        if (!$cancelStatus) {
            $cancelStatus = ShopOrderStatus::create([
                'code' => 'C',
                'name' => __('shop::default.canceled'),
            ]);
        }

        $order->order_status_id = $cancelStatus->id;
        $order->save();

        $this->message = __('shop::default.order_canceled');
        $this->error = false;

        return true;
    }

    public function testimonials(null|int|ShopProduct $product = null)
    {
        if (empty($this->customer) || empty($this->customer->id)) {
            return [];
        }
        $testimonials = ShopTestimonial::where('customer_id', $this->customer->id);
        if ($product) {
            if ($product instanceof ShopProduct) {
                $product = $product->id;
            }
            $testimonials->where('product_id', $product);
        }
        return $testimonials->pluck('id')->toArray() ?? [];
    }

    public function testimonialLikes(null|int|ShopProduct $product = null)
    {
        if (empty($this->customer) || empty($this->customer->id)) {
            return [];
        }
        $testimonialLikes = ShopTestimonialLike::where('customer_id', $this->customer->id);
        if ($product) {
            if ($product instanceof ShopProduct) {
                $product = $product->id;
            }
            $testimonialLikes->whereHas('testimonial', function ($query) use ($product) {
                $query->where('product_id', $product);
            });
        }
        $testimonialLikes = $testimonialLikes->get();
        $result = [];
        foreach ($testimonialLikes as $testimonialLike) {
            $like = 0;
            if ($testimonialLike->useful) {
                $like = 1;
            } elseif ($testimonialLike->useless) {
                $like = -1;
            }
            $result[$testimonialLike->testimonial_id] = $like;
        }
        return $result;
    }
}
