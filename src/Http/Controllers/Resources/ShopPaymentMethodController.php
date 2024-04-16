<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopPaymentMethodController extends Controller
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
            $payments = ShopPaymentMethod::where('name','like',"%{$search}%")->orderBy($order, $trend)->paginate($count);
        }
        else {
            $payments = ShopPaymentMethod::all();
        }

        return view('elfcms::admin.shop.payment-method.index',[
            'page' => [
                'title' => __('shop::default.payment_methods'),
                'current' => url()->current(),
            ],
            'payments' => $payments,
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
        return view('elfcms::admin.shop.payment-method.create',[
            'page' => [
                'title' => __('shop::default.create_payment_method'),
                'current' => url()->current(),
            ],
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
            'code' => 'required|unique:Elfcms\Shop\Models\ShopPaymentMethod,code',
            'icon' => 'nullable|file|max:1024',
        ]);

        $icon = '';
        if (!empty($request->file()['icon'])) {
            $icon = $request->file()['icon']->store('elfcms/shop/payment');
        }
        $validated['icon'] = $icon;

        $payment = ShopPaymentMethod::create($validated);

        return redirect(route('admin.shop.payment'))->with('action_result',__('shop::default.payment_method_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopPaymentMethod  $shopPaymentMethod
     * @return \Illuminate\Http\Response
     */
    public function show(Request  $request, ShopPaymentMethod $shopPaymentMethod)
    {
        if ($request->ajax()) {
            return $shopPaymentMethod;
        }
        else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopPaymentMethod  $shopPaymentMethod
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopPaymentMethod $shopPaymentMethod)
    {
        $shopPaymentMethod->created = '';
        $shopPaymentMethod->updated = '';
        if (!empty($shopPaymentMethod->created_at)) {
            $shopPaymentMethod->created = date('d.m.Y H:i:s',strtotime($shopPaymentMethod->created_at));
        }
        if (!empty($shopPaymentMethod->updated_at)) {
            $shopPaymentMethod->updated = date('d.m.Y H:i:s',strtotime($shopPaymentMethod->updated_at));
        }
        return view('elfcms::admin.shop.payment-method.edit',[
            'page' => [
                'title' => __('shop::default.edit_payment_method'),
                'current' => url()->current(),
            ],
            'paymentMethod' => $shopPaymentMethod
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopPaymentMethod  $shopPaymentMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopPaymentMethod $shopPaymentMethod)
    {
        $request->merge([
            'code' => Str::slug($request->code),
        ]);
        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required',//|unique:Elfcms\Shop\Models\ShopPaymentMethod,code',
        ]);

        $icon = $request->icon_path;
        if (!empty($request->file()['icon'])) {
            $icon = $request->file()['icon']->store('elfcms/shop/payment');
        }

        $shopPaymentMethod->icon = $icon;
        $shopPaymentMethod->name = $validated['name'];

        if ($shopPaymentMethod->save()) {
            return redirect(route('admin.shop.payment.edit',$shopPaymentMethod))->with('action_result',__('shop::default.payment_method_edited_successfully'));
        }
        return redirect(route('admin.shop.payment.edit'))->with('action_result',__('shop::default.error_of_payment_method_editing'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopPaymentMethod  $shopPaymentMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopPaymentMethod $shopPaymentMethod)
    {
        if (!$shopPaymentMethod->delete()) {
            return redirect(route('admin.shop.payment'))->withErrors(['paymentdelerror'=>__('shop::default.payment_method_deleted_successfully')]);
        }

        return redirect(route('admin.shop.payment'))->with('action_result',__('shop::default.error_of_payment_method_deleting'));
    }
}
