<?php

namespace Elfcms\Shop\Http\Controllers\Sellings;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\TmpUser;
use Elfcms\Elfcms\Models\User;
use Elfcms\Shop\Models\ShopCart;
use Elfcms\Shop\Models\ShopCartItem;
use Elfcms\Shop\Models\ShopCustomer;
use Elfcms\Shop\Models\ShopFavorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class SellingController extends Controller
{
    public $user;
    public $customer;
    public $tmp_user;
    public $cart;
    public $favorites;
    public $message = null;
    public $error = false;
    public $isAuth = false;
    public $customerNumber = null;

    public function __construct()
    {

        if (Auth::check()) {
            $this->user = User::with('data')->find(Auth::id());
            $this->customer = $this->setCustomer();
            $this->isAuth = true;
            $this->favorites = ShopFavorite::where('customer_id', $this->customer->id)->get() ?? [];
            $this->number();
        }
        else {
            $tmpUserCookie = Cookie::get('tmp_user');
            try {
                $this->tmp_user = TmpUser::where('uuid',$tmpUserCookie)->first();
                if (empty($this->tmp_user)) {
                    $this->tmp_user = TmpUser::create(['uuid'=>$tmpUserCookie]);
                }
                $this->favorites = ShopFavorite::where('tmp_user_id', $this->tmp_user->id)->get() ?? [];
            }
            catch (\Exception $e) {
                //
            }
        }
        $this->setCart();
    }

    public function number() {
        if (!empty($this->customer->number)) {
            $this->customerNumber = $this->customer->number;
            return $this->customer->number;
        }
        $number = $this->customer->id;
        if ($number < 10) {
            $number = '000'.$number;
        }
        elseif ($number < 100) {
            $number = '00'.$number;
        }
        elseif ($number < 1000) {
            $number = '0'.$number;
        }

        $date = $this->customer->created_at->format('Ym') ?? date('Ym');

        $control = array_sum(str_split($date . $number));

        while ($control >= 10) {
            $control = array_sum(str_split($control));
        }

        $number = $date . $control . $number;
        $this->customerNumber = $number;
        $this->customer->number = $number;
        $this->customer->update();

        return $number;
    }

    public function update() {
        $this->setCart();
        $this->favorites = ShopFavorite::where('customer_id', $this->customer->id)->get() ?? [];
    }

    public function favoritesMerge() {
        if (!empty($this->user) && !empty($this->user->tmp) && !empty($this->customer)) {
            $userFavorites = ShopFavorite::where('customer_id', $this->customer->id)->pluck('item_id')->toArray() ?? [];
            $tmpUser = TmpUser::where('uuid',$this->user->tmp)->first();
            if ($tmpUser) {
                $tmpFavorites = ShopFavorite::where('tmp_user_id', $tmpUser->id)->get() ?? [];
                foreach ($tmpFavorites as $tmpFavorite) {
                    if (!in_array($tmpFavorite->item_id, $userFavorites)) {
                        $tmpFavorite->customer_id = $this->customer->id;
                        $tmpFavorite->tmp_user_id = null;
                        $tmpFavorite->save();
                    }
                }
            }
        }
    }

    public function cartMerge() {
        if (!empty($this->user) && !empty($this->user->tmp) && !empty($this->customer) && !empty($this->cart)) {
            $tmpUser = TmpUser::where('uuid',$this->user->tmp)->first();
            if ($tmpUser) {
            $tmpCart = ShopCart::where('tmp_user_id',$tmpUser->id)->first();
            if ($tmpCart) {
                    $cartItems = ShopCartItem::whereIn('cart_id',[$this->cart->id,$tmpCart->id])->get();
                    $userCartItems = $cartItems->where('cart_id',$this->cart->id)->pluck('id')->toArray();
                    $tmpCartItems = $cartItems->where('cart_id',$tmpCart->id);
                    foreach ($tmpCartItems as $tmpCartItem) {
                        if (!in_array($tmpCartItem->id, $userCartItems)) {
                            $tmpCartItem->cart_id = $this->cart->id;
                            $tmpCartItem->save();
                        }
                    }
                }
            }
        }
    }

    public function cartRecheck() {
        if (!empty($this->cart) && !empty($this->cart->cartItems)) {
            $itemsById = [];
            foreach($this->cart->cartItems as $key => $item) {
                if (empty($itemsById[$item->item_id])) {
                    $itemsById[$item->item_id] = $item;
                }
                else {
                    $quantity = $itemsById[$item->item_id]->quantity + $item->quantity;
                    if ($quantity > $item->item->balance) {
                        $quantity = $item->item->balance;
                    }
                    $itemsById[$item->item_id]->quantity = $quantity;
                    $itemsById[$item->item_id]->save();
                    $item->delete();
                    $this->cart->cartItems->forget($key);
                }
            }
        }
    }

    public function cartCheckout() {
        if (!empty($this->cart) && !empty($this->cart->cartItems)) {
            $itemsById = [];
            foreach($this->cart->cartItems as $key => $item) {
                if (empty($itemsById[$item->item_id])) {
                    $itemsById[$item->item_id] = $item;
                }
                else {
                    $quantity = $itemsById[$item->item_id]->quantity + $item->quantity;
                    if ($quantity > $item->item->balance) {
                        $quantity = $item->item->balance;
                    }
                    $itemsById[$item->item_id]->quantity = $quantity;
                    $itemsById[$item->item_id]->save();
                    $item->delete();
                    $this->cart->cartItems->forget($key);
                }
                if (empty($item->item->balance)) {
                    $item->delete();
                    $this->cart->cartItems->forget($key);
                }
                if ($item->quantity > $item->item->balance) {
                    $item->quantity = $item->item->balance;
                    $item->save();
                }
            }
        }
    }

    protected function startResult() {
        $this->message = null;
        $this->error = false;
    }

    public function setCustomer()
    {
        $this->startResult();
        if (!$this->user) {
            $this->message = 'User not found';
            $this->error = true;
            return false;
        }

        if (!$this->customer) {
            $this->customer = ShopCustomer::updateOrCreate(
                ['user_id' => $this->user->id],
                [
                    'firstname' => $this->user->data->first_name,
                    'lastname' => $this->user->data->last_name,
                    'birthday' => $this->user->data->birthday,
                    'phone' => $this->user->data->phone_code . $this->user->data->phone_number,
                ]
            );
        }
        //dd([$this->user,$this->customer]);
        return $this->customer ?? null;
    }

    public function setCart() {
        $this->startResult();
        if ($this->tmp_user && $this->tmp_user->id) {
            $this->cart = ShopCart::firstOrCreate(
                ['tmp_user_id' => $this->tmp_user->id],
                ['customer_id' => $this->customer?->id]
            );
        }
        if (!$this->cart && $this->customer && $this->customer->id) {
            $this->cart = ShopCart::firstOrCreate(
                ['customer_id' => $this->customer->id],
                ['tmp_user_id' => $this->tmp_user?->id]
            );
        }
        elseif ($this->cart && !$this->cart->customer_id) {
            $this->cart->customer_id = $this->customer?->id;
        }
        return $this->cart ?? null;
    }

}
