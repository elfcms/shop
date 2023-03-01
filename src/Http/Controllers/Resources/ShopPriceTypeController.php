<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopPriceType;
use Elfcms\Shop\Models\ShopTax;
use Illuminate\Http\Request;

class ShopPriceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $price_types = ShopPriceType::all();
        $taxes = ShopTax::all();
        return view('shop::admin.shop.price-type.index',[
            'page' => [
                'title' => __('shop::elf.price_types'),
                'current' => url()->current(),
            ],
            'price_types' => $price_types,
            'taxes' => $taxes,
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
     * @param  \App\Models\ShopPriceType  $shopPriceType
     * @return \Illuminate\Http\Response
     */
    public function show(ShopPriceType $shopPriceType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopPriceType  $shopPriceType
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopPriceType $shopPriceType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopPriceType  $shopPriceType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopPriceType $shopPriceType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopPriceType  $shopPriceType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopPriceType $shopPriceType)
    {
        //
    }
}
