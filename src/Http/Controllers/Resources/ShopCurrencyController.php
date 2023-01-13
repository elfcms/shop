<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopCurrency;
use Illuminate\Http\Request;

class ShopCurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencies = ShopCurrency::all();
        return view('shop::admin.shop.currency.index',[
            'page' => [
                'title' => __('shop::elf.currencies'),
                'current' => url()->current(),
            ],
            'currencies' => $currencies,
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
     * @param  \App\Models\ShopCurrency  $shopCurrency
     * @return \Illuminate\Http\Response
     */
    public function show(ShopCurrency $shopCurrency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopCurrency  $shopCurrency
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopCurrency $shopCurrency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopCurrency  $shopCurrency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopCurrency $shopCurrency)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopCurrency  $shopCurrency
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopCurrency $shopCurrency)
    {
        //
    }
}
