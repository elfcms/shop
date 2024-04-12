<?php

namespace Elfcms\Shop\Http\Controllers\Sellings;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\TmpUser;
use Elfcms\Shop\Models\ShopCart;
use Elfcms\Shop\Models\ShopCoupon;
use Elfcms\Shop\Models\ShopCustomer;
use Elfcms\Shop\Models\ShopUsedCoupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CouponController extends Controller
{
    static private $letterValues = [
        'a' => 6,
        'b' => 11,
        'c' => 12,
        'd' => 13,
        'e' => 5,
        'f' => 14,
        'g' => 15,
        'h' => 16,
        'i' => 4,
        'j' => 18,
        'k' => 19,
        'l' => 20,
        'm' => 21,
        'n' => 22,
        'o' => 3,
        'p' => 23,
        'q' => 24,
        'r' => 25,
        's' => 26,
        't' => 27,
        'u' => 2,
        'v' => 28,
        'w' => 29,
        'x' => 30,
        'y' => 1,
        'z' => 31
    ];
    static private $letters = 'abcdefghijklmnopqrstuvwxyz';
    static private $numbers = '0123456789';
    static private $symbols = 'abcdefghijklmnopqrstuvwxyz0123456789';

    static public function newCode($pattern = null, $length = null, $check = false)
    {
        $code = self::createCode($pattern, $length, $check);
        while (!empty(ShopCoupon::where('code', $code)->first())) {
            $code = self::createCode($pattern, $length, $check);
        }

        return $code;
    }

    static public function createCode($pattern = null, $length = null, $check = false)
    {
        $symbols = str_split(self::$symbols);
        $numbers = str_split(self::$numbers);
        $letters = str_split(self::$letters);
        $length = (int)$length;
        if ($pattern == null && $length == 0) {
            $pattern = 'SSSSS-SS';
            if (!$check) {
                $pattern .= 'SSS';
            }
        }
        $code = '';
        if ($pattern == null && $length > 0) {
            if ($length > 30) {
                $length = 30;
            }
            for ($i = 0; $i < $length; $i++) {
                $code .= $symbols[rand(0, count($symbols) - 1)];
            }
        } else {
            $pattern = str_split($pattern);
            $sum = 0;
            foreach ($pattern as $key => $value) {
                if (strtoupper($value) == 'S') {
                    $code .= $symbols[rand(0, count($symbols) - 1)];
                } elseif (strtoupper($value) == 'D') {
                    $code .= $numbers[rand(0, count($numbers) - 1)];
                } elseif (strtoupper($value) == 'A') {
                    $code .= $letters[rand(0, count($letters) - 1)];
                } elseif (strtoupper($value) == 'H') {
                    $code .= dechex(rand(0, 15));
                } elseif (strtoupper($value) == '/') {
                    $code .= '/';
                } elseif (strtoupper($value) == '-') {
                    $code .= '-';
                }
            }
        }
        if (!empty($code)) {
            if ($check) {
                $chk = self::checkSum($code);
                if ($chk < 10) {
                    $chk = '00' . $chk;
                } elseif ($chk < 100) {
                    $chk = '0' . $chk;
                }
                $code .= $chk;
            }
        }
        /* $pattern = str_split($pattern);
        $length = count($pattern); */
        return strtoupper($code);
    }

    static public function checkSum($code)
    {
        $sum = 0;
        $code = str_split(strtolower($code));
        if (!empty($code)) {
            foreach ($code as $c) {
                if (is_numeric($c)) {
                    $sum += $c;
                } elseif (!empty(self::$letterValues[$c])) {
                    $sum += self::$letterValues[$c];
                }
            }
        }
        return $sum;
    }

    static public function checkCode($code)
    {
        $code = strtolower($code);
        $sum = (int)substr($code, -3);
        $code = substr($code, 0, -3);
        if ($sum == self::checkSum($code)) {
            return true;
        } else {
            return false;
        }
    }

    static public function checkCoupon($code, $check = false)
    {
        if (empty($code)) {
            return false;
        }
        $checkResult = true;
        if ($check) {
            $checkResult = self::checkCode($code);
        }
        if (!$checkResult) {
            return false;
        }
        $coupon = ShopCoupon::where('code', $code)->first();
        if (empty($coupon)) {
            return false;
        }
        if ($coupon->used->count() >= $coupon->uses_number) {
            return false;
        }
        return $coupon;
    }

    static public function useCoupon($code, $customerId, $check = false)
    {
        $coupon = self::checkCoupon($code, $check);
        if (empty($coupon)) {
            return false;
        }
        $usedCoupon = ShopUsedCoupon::create(['coupon_id'=>$coupon->id,'customer_id'=>$customerId,'code'=>$code]);

        return $usedCoupon;
    }
}
