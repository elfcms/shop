<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopDeliveryController extends Controller
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
            $deliveries = ShopDelivery::where('name', 'like', "%{$search}%")->orderBy($order, $trend)->paginate($count);
        } else {
            $deliveries = ShopDelivery::all();
        }

        return view('elfcms::admin.shop.delivery.index', [
            'page' => [
                'title' => __('shop::default.delivery_services'),
                'current' => url()->current(),
            ],
            'deliveries' => $deliveries,
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
        return view('elfcms::admin.shop.delivery.create', [
            'page' => [
                'title' => __('shop::default.create_delivery_service'),
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
            'code' => 'required|unique:Elfcms\Shop\Models\ShopDelivery,code',
            'icon' => 'nullable|file|max:1024',
        ]);

        $icon = '';
        if (!empty($request->file()['icon'])) {
            $icon = $request->file()['icon']->store('elfcms/shop/delivery');
        }
        $validated['icon'] = $icon;
        $validated['price'] = (float)$request->price * 100;

        $delivery = ShopDelivery::create($validated);

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.shop.delivery'))->with('action_result', __('shop::default.delivery_service_created_successfully'));
        }

        return redirect(route('admin.shop.delivery.edit', ['shopDelivery' => $delivery]))->with('success', __('shop::default.delivery_service_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopDelivery  $shopDelivery
     * @return \Illuminate\Http\Response
     */
    public function show(Request  $request, ShopDelivery $shopDelivery)
    {
        if ($request->ajax()) {
            return $shopDelivery;
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopDelivery  $shopDelivery
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopDelivery $shopDelivery)
    {
        $shopDelivery->created = '';
        $shopDelivery->updated = '';
        if (!empty($shopDelivery->created_at)) {
            $shopDelivery->created = date('d.m.Y H:i:s', strtotime($shopDelivery->created_at));
        }
        if (!empty($shopDelivery->updated_at)) {
            $shopDelivery->updated = date('d.m.Y H:i:s', strtotime($shopDelivery->updated_at));
        }
        return view('elfcms::admin.shop.delivery.edit', [
            'page' => [
                'title' => __('shop::default.edit_delivery_service'),
                'current' => url()->current(),
            ],
            'delivery' => $shopDelivery
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopDelivery  $shopDelivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopDelivery $shopDelivery)
    {
        $request->merge([
            'code' => Str::slug($request->code),
        ]);
        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required', //|unique:Elfcms\Shop\Models\ShopDelivery,code',
        ]);

        $icon = $request->icon_path;
        if (!empty($request->file()['icon'])) {
            $icon = $request->file()['icon']->store('elfcms/shop/delivery');
        }

        $shopDelivery->icon = $icon;
        $shopDelivery->name = $validated['name'];
        $shopDelivery->price = (float)$request->price * 100;

        if ($shopDelivery->save()) {
            if ($request->input('submit') == 'save_and_close') {
                return redirect(route('admin.shop.delivery'))->with('action_result', __('shop::default.delivery_service_edited_successfully'));
            }
            return redirect(route('admin.shop.delivery.edit', $shopDelivery))->with('success', __('shop::default.delivery_service_edited_successfully'));
        }
        return redirect(route('admin.shop.delivery.edit'))->withErrors(['error'=> __('shop::default.error_of_delivery_service_editing')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopDelivery  $shopDelivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopDelivery $shopDelivery)
    {
        if (!$shopDelivery->delete()) {
            return redirect(route('admin.shop.delivery'))->withErrors(['deliverydelerror' => __('shop::default.delivery_service_deleted_successfully')]);
        }

        return redirect(route('admin.shop.delivery'))->with('action_result', __('shop::default.error_of_delivery_service_deleting'));
    }
}
