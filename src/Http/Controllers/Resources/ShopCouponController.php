<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Http\Controllers\Sellings\CouponController;
use Elfcms\Shop\Http\Controllers\Sellings\CustomerController;
use Elfcms\Shop\Models\ShopCoupon;
use Elfcms\Shop\Models\ShopCurrency;
use Elfcms\Shop\Models\ShopCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $trend = 'asc';
        $order = 'id';
        if (!empty($request->trend) && $request->trend == 'desc') {
            $trend = 'desc';
        }
        if (!empty($request->order)) {
            $order = $request->order;
        }
        if (!empty($request->count)) {
            $count = intval($request->count);
        }
        if (empty($count)) {
            $count = 30;
        }
        $search = $request->search ?? '';

        if (!empty($search)) {
            $coupons = ShopCoupon::where('name', 'like', "%{$search}%")->orderBy($order, $trend)->paginate($count);
        } else {
            $coupons = ShopCoupon::all();
        }

        return view('elfcms::admin.shop.coupon.index', [
            'page' => [
                'title' => __('shop::default.coupons'),
                'current' => url()->current(),
            ],
            'coupons' => $coupons,
            'search' => $search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd(CouponController::createCode('AAA-SSSSS-DDD-HHH'));
        //dd(CouponController::checkCode('czv83-t4113'));
        //dd(ShopCoupon::where('code','asdasdasd')->first());
        //dd(CouponController::newCode(check:true));
        //$c = ShopCustomer::all();
        //dd($c[0]->user?->id);
        return view('elfcms::admin.shop.coupon.create', [
            'page' => [
                'title' => __('shop::default.create_coupon'),
                'current' => url()->current(),
            ],
            'code' => CouponController::newCode(check: true),
            'currencies' => ShopCurrency::all(),
            'customers' => ShopCustomer::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required',
            'value' => 'required',
            'description' => 'nullable',
            'is_percent' => 'nullable',
            'min_price' => 'nullable',
            'max_price' => 'nullable',
            'min_value' => 'nullable',
            'min_is_percent' => 'nullable',
            'max_value' => 'nullable',
            'max_is_percent' => 'nullable',
            'uses_number' => 'nullable',
            'currency_id' => 'nullable',
            'valid_from' => 'nullable',
            'valid_until' => 'nullable',
            'customer_id' => 'nullable',
            'combine' => 'nullable',
        ]);

        $valid_from = $request->valid_from[0];

        if (empty($request->valid_from[1]) && !empty($valid_from)) {
            $valid_from .= ' 00:00:00';
        } elseif (!empty($valid_from)) {
            $valid_from .= ' ' . $request->valid_from[1];
        }

        $valid_until = $request->valid_until[0];

        if (empty($request->valid_until[1]) && !empty($valid_until)) {
            $valid_until .= ' 00:00:00';
        } elseif (!empty($valid_until)) {
            $valid_until .= ' ' . $request->valid_until[1];
        }

        $validated['valid_from'] = $valid_from;
        $validated['valid_until'] = $valid_until;

        $validated['is_percent'] = (int)boolval($request->is_percent);
        $validated['combine'] = (int)boolval($request->combine);
        $validated['max_is_percent'] = (int)boolval($request->max_is_percent);
        $validated['min_is_percent'] = (int)boolval($request->min_is_percent);

        $coupon = ShopCoupon::create($validated);

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.shop.coupon'))->with('action_result', __('shop::default.coupon_created_successfully'));
        }

        return redirect(route('admin.shop.coupon.edit',['shopCoupon'=>$coupon]))->with('success', __('shop::default.coupon_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopCoupon  $shopCoupon
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ShopCoupon $shopCoupon)
    {
        if ($request->ajax()) {
            return $shopCoupon;
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopCoupon  $shopCoupon
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopCoupon $shopCoupon)
    {
        //dd($shopCoupon->used->count());
        $shopCoupon->created = '';
        $shopCoupon->updated = '';
        if (!empty($shopCoupon->created_at)) {
            $shopCoupon->created = date('d.m.Y H:i:s', strtotime($shopCoupon->created_at));
        }
        if (!empty($shopCoupon->updated_at)) {
            $shopCoupon->updated = date('d.m.Y H:i:s', strtotime($shopCoupon->updated_at));
        }

        if (!empty($shopCoupon->valid_until)) {
            $shopCoupon->valid_until_time = date('H:i', strtotime($shopCoupon->valid_until));
            $shopCoupon->valid_until = date('Y-m-d', strtotime($shopCoupon->valid_until));
        }
        if (!empty($shopCoupon->valid_from)) {
            $shopCoupon->valid_from_time = date('H:i', strtotime($shopCoupon->valid_from));
            $shopCoupon->valid_from = date('Y-m-d', strtotime($shopCoupon->valid_from));
        }
        return view('elfcms::admin.shop.coupon.edit', [
            'page' => [
                'title' => __('shop::default.edit_coupon'),
                'current' => url()->current(),
            ],
            'coupon' => $shopCoupon,
            'currencies' => ShopCurrency::all(),
            'customers' => ShopCustomer::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopCoupon  $shopCoupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopCoupon $shopCoupon)
    {
        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required',
            'value' => 'required',
        ]);

        $valid_from = $request->valid_from[0];
        if (empty($request->valid_from[1]) && !empty($valid_from)) {
            $valid_from .= ' 00:00:00';
        } elseif (!empty($valid_from)) {
            $valid_from .= ' ' . $request->valid_from[1];
        }

        $valid_until = $request->valid_until[0];
        if (empty($request->valid_until[1]) && !empty($valid_until)) {
            $valid_until .= ' 00:00:00';
        } elseif (!empty($valid_until)) {
            $valid_until .= ' ' . $request->valid_until[1];
        }

        $shopCoupon->valid_from = $valid_from;
        $shopCoupon->valid_until = $valid_until;
        $shopCoupon->is_percent = (int)boolval($request->is_percent);
        $shopCoupon->combine = (int)boolval($request->combine);
        $shopCoupon->max_is_percent = (int)boolval($request->max_is_percent);
        $shopCoupon->min_is_percent = (int)boolval($request->min_is_percent);

        $shopCoupon->name = $validated['name'];
        $shopCoupon->code = $validated['code'];
        $shopCoupon->value = $validated['value'];
        $shopCoupon->description = $request->description;
        $shopCoupon->min_price = $request->min_price;
        $shopCoupon->max_price = $request->max_price;
        $shopCoupon->min_value = $request->min_value;
        $shopCoupon->max_value = $request->max_value;
        $shopCoupon->uses_number = (int)$request->uses_number;
        $shopCoupon->customer_id = $request->customer_id;
        $shopCoupon->currency_id = $request->currency_id;

        $shopCoupon->save();

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.shop.coupon'))->with('action_result', __('shop::default.coupon_edited_successfully'));
        }

        return redirect(route('admin.shop.coupon.edit', $shopCoupon))->with('success', __('shop::default.coupon_edited_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopCoupon  $shopCoupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopCoupon $shopCoupon)
    {
        if (!$shopCoupon->delete()) {
            return redirect(route('admin.shop.coupon'))->withErrors(['coupondelerror' => __('shop::default.error_of_coupon_deleting')]);
        }

        return redirect(route('admin.shop.coupon'))->with('action_result', __('shop::default.coupon_deleted_successfully'));
    }
}
