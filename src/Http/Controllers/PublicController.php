<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopProduct;

class PublicController extends Controller
{
    public function __invoke()
    {
        $products = ShopProduct::with('items')->get();
        //dd($products);
        return view(
            'shop::public.index',
            [
                'products' => $products
            ]
        );
    }
}
