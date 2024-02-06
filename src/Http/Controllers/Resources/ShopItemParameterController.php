<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\DataType;
use Elfcms\Shop\Models\ShopItemParameter;
use Illuminate\Http\Request;

class ShopItemParameterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataTypes = DataType::all();
        $parameters = ShopItemParameter::all();
        return view('elfcms::admin.shop.parameter.index',[
            'page' => [
                'title' => __('shop::default.parameters'),
                'current' => url()->current(),
            ],
            'parameters' => $parameters,
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
     * @param  \App\Models\ShopItemParameter  $shopItemParameter
     * @return \Illuminate\Http\Response
     */
    public function show(ShopItemParameter $shopItemParameter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopItemParameter  $shopItemParameter
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopItemParameter $shopItemParameter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopItemParameter  $shopItemParameter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopItemParameter $shopItemParameter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopItemParameter  $shopItemParameter
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopItemParameter $shopItemParameter)
    {
        //
    }
}
