<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopTax;
use Illuminate\Http\Request;

class ShopTaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taxes = ShopTax::all();
        return view('elfcms::admin.shop.tax.index',[
            'page' => [
                'title' => __('shop::default.taxes'),
                'current' => url()->current(),
            ],
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
     * @param  \App\Models\ShopTax  $shopTax
     * @return \Illuminate\Http\Response
     */
    public function show(ShopTax $shopTax)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopTax  $shopTax
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopTax $shopTax)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopTax  $shopTax
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopTax $shopTax)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopTax  $shopTax
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopTax $shopTax)
    {
        //
    }
}
