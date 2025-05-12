<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopCategory;
use Elfcms\Shop\Models\ShopDiscount;
use Illuminate\Http\Request;

class ShopDiscountController extends Controller
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
            $discounts = ShopDiscount::where('name', 'like', "%{$search}%")->orderBy($order, $trend)->paginate($count);
        } else {
            $discounts = ShopDiscount::all();
        }

        return view('elfcms::admin.shop.discount.index', [
            'page' => [
                'title' => __('shop::default.discounts'),
                'current' => url()->current(),
            ],
            'discounts' => $discounts,
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
        return view('elfcms::admin.shop.discount.create', [
            'page' => [
                'title' => __('shop::default.create_discount'),
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
            'value' => 'required',
        ]);

        $discount = ShopDiscount::create($validated);

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.shop.discount'))->with('action_result', __('shop::default.discount_created_successfully'));
        }

        return redirect(route('admin.shop.discount.edit',['shopDiscount'=>$discount]))->with('success', __('shop::default.discount_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShopDiscount  $shopDiscount
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ShopDiscount $shopDiscount)
    {
        if ($request->ajax()) {
            return $shopDiscount;
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopDiscount  $shopDiscount
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopDiscount $shopDiscount)
    {
        $shopDiscount->created = '';
        $shopDiscount->updated = '';
        if (!empty($shopDiscount->created_at)) {
            $shopDiscount->created = date('d.m.Y H:i:s', strtotime($shopDiscount->created_at));
        }
        if (!empty($shopDiscount->updated_at)) {
            $shopDiscount->updated = date('d.m.Y H:i:s', strtotime($shopDiscount->updated_at));
        }

        return view('elfcms::admin.shop.discount.edit', [
            'page' => [
                'title' => __('shop::default.edit_discount'),
                'current' => url()->current(),
            ],
            'discount' => $shopDiscount,
            'categories' => ShopCategory::flat(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopDiscount  $shopDiscount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopDiscount $shopDiscount)
    {
        $validated = $request->validate([
            'name' => 'required',
            'value' => 'required',
        ]);

        $shopDiscount->name = $validated['name'];
        $shopDiscount->value = $validated['value'];

        $shopDiscount->save();

        if ($request->input('submit') == 'save_and_close') {
            return redirect(route('admin.shop.discount'))->with('action_result', __('shop::default.discount_edited_successfully'));
        }

        return redirect(route('admin.shop.discount.edit', $shopDiscount))->with('success', __('shop::default.discount_edited_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopDiscount  $shopDiscount
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopDiscount $shopDiscount)
    {
        if (!$shopDiscount->delete()) {
            return redirect(route('admin.shop.discount'))->withErrors(['discountdelerror' => __('shop::default.error_of_discount_deleting')]);
        }

        return redirect(route('admin.shop.discount'))->with('action_result', __('shop::default.discount_deleted_successfully'));
    }
}
