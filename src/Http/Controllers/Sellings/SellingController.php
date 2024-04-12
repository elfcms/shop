<?php

namespace Elfcms\Shop\Http\Controllers\Sellings;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\TmpUser;
use Elfcms\Shop\Models\ShopCart;
use Elfcms\Shop\Models\ShopCustomer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class SellingController extends Controller
{
    public $user;
    public $customer;
    public $tmp_user;
    public $cart;

    public function __construct()
    {
        if (Auth::check()) {
            $this->user = Auth::user();
            $this->customer = $this->setCustomer();
        }
        else {
            $tmpUserCookie = Cookie::get('tmp_user');
            try {
                $this->tmp_user = TmpUser::where('uuid',$tmpUserCookie)->first();
                if (empty($this->tmp_user)) {
                    $this->tmp_user = TmpUser::create(['uuid'=>$tmpUserCookie]);
                }
            }
            catch (\Exception $e) {
                //
            }
        }
        $this->setCart();
    }

    public function setCustomer()
    {
        if (!$this->user) return false;

        if (!$this->customer) {
            $this->customer = ShopCustomer::firstOrCreate(
                ['user_id' => $this->user->id],
                [
                    'firstname' => $this->user->data->first_name,
                    'lastname' => $this->user->data->last_name,
                    'birthday' => $this->user->data->birthday,
                    'phone' => $this->user->data->phone_code . $this->user->data->phone_number,
                ]
            );
        }

        return $this->customer ?? null;
    }

    public function setCart() {
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
