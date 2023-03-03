<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopCurrency;
use Elfcms\Shop\Models\ShopPriceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopPriceController extends Controller
{

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emptyItem(Request  $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $id = $request->id ?? uniqid();
        $emptyItem = [
            'id' => $id,
            'price_type_id' => null,
            'value' => null,
            'currency_id' => null,
            'current' => null,
            'old' => null
        ];

        $price_types = ShopPriceType::all();
        $currencies = ShopCurrency::all();

        return view(
            'shop::admin.shop.item.content.priceitem',
            [
                'price' => (object)$emptyItem,
                'price_types' => $price_types,
                'currencies' => $currencies
            ]
        );
    }

}
