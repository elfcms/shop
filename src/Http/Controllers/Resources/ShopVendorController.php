<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopVendorController extends Controller
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
            $vendors = ShopVendor::where('name','like',"%{$search}%")->orderBy($order, $trend)->paginate($count);
        }
        else {
            $vendors = ShopVendor::all();
        }

        return view('elfcms::admin.shop.vendor.index',[
            'page' => [
                'title' => __('shop::default.vendors'),
                'current' => url()->current(),
            ],
            'vendors' => $vendors,
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
        return view('elfcms::admin.shop.vendor.create',[
            'page' => [
                'title' => __('shop::default.create_vendor'),
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
            'name' => 'required'
        ]);

        $vendor = ShopVendor::create($validated);

        return redirect(route('admin.shop.vendor'))->with('action_result',__('shop::default.vendor_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopVendor  $shopVendor
     * @return \Illuminate\Http\Response
     */
    public function show(Request  $request, ShopVendor $shopVendor)
    {
        if ($request->ajax()) {
            return $shopVendor;
        }
        else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopVendor  $shopVendor
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopVendor $shopVendor)
    {
        $shopVendor->created = '';
        $shopVendor->updated = '';
        if (!empty($shopVendor->created_at)) {
            $shopVendor->created = date('d.m.Y H:i:s',strtotime($shopVendor->created_at));
        }
        if (!empty($shopVendor->updated_at)) {
            $shopVendor->updated = date('d.m.Y H:i:s',strtotime($shopVendor->updated_at));
        }
        return view('elfcms::admin.shop.vendor.edit',[
            'page' => [
                'title' => __('shop::default.edit_vendor'),
                'current' => url()->current(),
            ],
            'vendor' => $shopVendor
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopVendor  $shopVendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopVendor $shopVendor)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $shopVendor->name = $validated['name'];

        $shopVendor->save();

        return redirect(route('admin.shop.vendor.edit',$shopVendor))->with('action_result',__('shop::default.vendor_edited_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopVendor  $shopVendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopVendor $shopVendor)
    {
        if (!$shopVendor->delete()) {
            return redirect(route('admin.shop.vendor'))->withErrors(['vendordelerror'=>__('shop::default.error_of_vendor_deleting')]);
        }

        return redirect(route('admin.shop.vendor'))->with('action_result',__('shop::default.vendor16712_deleted_successfully'));
    }
}
