<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopUnit;
use Illuminate\Http\Request;

class ShopUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = ShopUnit::all();
        return view('shop::admin.shop.unit.index',[
            'page' => [
                'title' => __('shop::elf.units_of_measurement'),
                'current' => url()->current(),
            ],
            'units' => $units,
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
     * @param  \App\Models\ShopUnit  $shopUnit
     * @return \Illuminate\Http\Response
     */
    public function show(ShopUnit $shopUnit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopUnit  $shopUnit
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopUnit $shopUnit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopUnit  $shopUnit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopUnit $shopUnit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopUnit  $shopUnit
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopUnit $shopUnit)
    {
        //
    }
}
