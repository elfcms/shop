<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopCategory;
use Elfcms\Shop\Models\ShopProduct;
use Elfcms\Shop\Models\ShopItem;
use Elfcms\Shop\Models\ShopItemParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellingController extends Controller
{
    public function index()
    {
        return view('elfcms::admin.shop.selling.index',[
            'page' => [
                'title' => __('shop::default.selling'),
                'current' => url()->current(),
            ],
            //'menuData' => $menuData,
        ]);
    }

}
