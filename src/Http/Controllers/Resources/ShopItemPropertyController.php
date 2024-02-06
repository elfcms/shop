<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\DataType;
use Elfcms\Shop\Models\ShopItemProperty;
use Illuminate\Http\Request;

class ShopItemPropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataTypes = DataType::all();
        $properties = ShopItemProperty::all();
        return view('elfcms::admin.shop.property.index',[
            'page' => [
                'title' => __('shop::default.properties'),
                'current' => url()->current(),
            ],
            'properties' => $properties,
            'data_types' => $dataTypes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShopItemProperty  $shopItemProperty
     * @return \Illuminate\Http\Response
     */
    public function show(ShopItemProperty $shopItemProperty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopItemProperty  $shopItemProperty
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopItemProperty $shopItemProperty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopItemProperty  $shopItemProperty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopItemProperty $shopItemProperty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopItemProperty  $shopItemProperty
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopItemProperty $shopItemProperty)
    {
        //
    }
}
