<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopReturnReason;
use Illuminate\Http\Request;

class ShopReturnReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reasons = ShopReturnReason::all();
        return view('elfcms::admin.shop.return-reason.index',[
            'page' => [
                'title' => __('shop::default.reasons_for_return'),
                'current' => url()->current(),
            ],
            'reasons' => $reasons,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ShopReturnReason $shopReturnReason)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShopReturnReason $shopReturnReason)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShopReturnReason $shopReturnReason)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShopReturnReason $shopReturnReason)
    {
        //
    }
}
