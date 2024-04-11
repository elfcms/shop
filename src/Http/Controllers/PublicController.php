<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Http\Controllers\Sellings\CustomerController;
use Elfcms\Shop\Http\Controllers\Sellings\SellingController;
use Elfcms\Shop\Models\ShopFavorite;
use Elfcms\Shop\Models\ShopItem;
use Elfcms\Shop\Models\ShopProduct;

class PublicController extends Controller
{
    public function __invoke()
    {
        /* $products = ShopProduct::with('items')->get();
        //dd($products[0]->items[1]->publicPrices->first->currencySymbol);
        return view(
            'shop::public.index',
            [
                'products' => $products
            ]
        ); */
        //$sc = new SellingController();
        //dd($sc);
        $cl = new CustomerController();
        //dd($cl->setFavorite(ShopItem::find(3)));
        //dd($cl->unsetFavorite(ShopFavorite::find(3)));
        //dd($cl->unsetFavoriteItem(ShopItem::find(3)));
    }
}
