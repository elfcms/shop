<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopBrandController extends Controller
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
            $brands = ShopBrand::where('name','like',"%{$search}%")->orderBy($order, $trend)->paginate($count);
        }
        else {
            $brands = ShopBrand::all();
        }

        return view('elfcms::admin.shop.brand.index',[
            'page' => [
                'title' => __('shop::default.brands'),
                'current' => url()->current(),
            ],
            'brands' => $brands,
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
        return view('elfcms::admin.shop.brand.create',[
            'page' => [
                'title' => __('shop::default.create_brand'),
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
        $request->merge([
            'slug' => Str::slug($request->slug),
        ]);
        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:Elfcms\Shop\Models\ShopBrand,slug',
            'logo' => 'nullable|file|max:512',
            'image' => 'nullable|file|max:1024',
        ]);

        $image_path = '';
        if (!empty($request->file()['image'])) {
            $image_path = $request->file()['image']->store('shop/brand/image');
        }

        $logo_path = '';
        if (!empty($request->file()['logo'])) {
            $logo_path = $request->file()['logo']->store('shop/brand/logo');
        }

        $validated['logo'] = $logo_path;
        $validated['image'] = $image_path;
        $validated['company'] = $request->company;
        $validated['description'] = $request->description;
        $validated['active'] = empty($request->active) ? 0 : 1;

        $brand = ShopBrand::create($validated);

        return redirect(route('admin.shop.brand'))->with('action_result',__('shop::default.brand_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopBrand  $shopBrand
     * @return \Illuminate\Http\Response
     */
    public function show(Request  $request, ShopBrand $shopBrand)
    {
        if ($request->ajax()) {
            return $shopBrand;
        }
        else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopBrand  $shopBrand
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopBrand $shopBrand)
    {
        $shopBrand->created = '';
        $shopBrand->updated = '';
        if (!empty($shopBrand->created_at)) {
            $shopBrand->created = date('d.m.Y H:i:s',strtotime($shopBrand->created_at));
        }
        if (!empty($shopBrand->updated_at)) {
            $shopBrand->updated = date('d.m.Y H:i:s',strtotime($shopBrand->updated_at));
        }
        return view('elfcms::admin.shop.brand.edit',[
            'page' => [
                'title' => __('shop::default.edit_brand'),
                'current' => url()->current(),
            ],
            'brand' => $shopBrand
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopBrand  $shopBrand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopBrand $shopBrand)
    {
        $request->merge([
            'slug' => Str::slug($request->slug),
        ]);
        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'logo' => 'nullable|file|max:512',
            'image' => 'nullable|file|max:1024',
        ]);
        if (ShopBrand::where('slug',$request->slug)->where('id','<>',$shopBrand->id)->first()) {
            return redirect(route('admin.shop.category.edit'))->withErrors([
                'slug' => 'Category already exists'
            ]);
        }
        $logo_path = $request->logo_path;
        if (!empty($request->file()['logo'])) {
            $logo_path = $request->file()['logo']->store('shop/brand/logo');
        }
        $image_path = $request->image_path;
        if (!empty($request->file()['image'])) {
            $image_path = $request->file()['image']->store('shop/brand/image');
        }

        $shopBrand->name = $validated['name'];
        $shopBrand->slug = $validated['slug'];
        $shopBrand->logo = $logo_path;
        $shopBrand->image = $image_path;
        $shopBrand->description = $request->description;
        $shopBrand->active = empty($request->active) ? 0 : 1;

        $shopBrand->save();

        return redirect(route('admin.shop.brand.edit',$shopBrand))->with('action_result',__('shop::default.brand_edited_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopBrand  $shopBrand
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopBrand $shopBrand)
    {
        if (!$shopBrand->delete()) {
            return redirect(route('admin.shop.brand'))->withErrors(['branddelerror'=>__('shop::default.error_of_brand_deleting')]);
        }

        return redirect(route('admin.shop.brand'))->with('action_result',__('shop::default.brand16712_deleted_successfully'));
    }
}
