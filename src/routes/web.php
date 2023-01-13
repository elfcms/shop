<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$adminPath = Config::get('elfcms.basic.admin_path') ?? '/admin';

Route::group(['middleware'=>['web','cookie','start']],function() use ($adminPath) {

    Route::name('admin.')->middleware('admin')->group(function() use ($adminPath) {

        Route::name('shop.')->group(function() use ($adminPath) {
            Route::get($adminPath . '/shop', [\Elfcms\Shop\Http\Controllers\AdminController::class,'shop'])->name('index');

            Route::resource($adminPath . '/shop/category', \Elfcms\Shop\Http\Controllers\Resources\ShopCategoryController::class)
                ->parameters(['category'=>'shopCategory'])
                ->names(['index'=>'category']);

            Route::resource($adminPath . '/shop/goods', \Elfcms\Shop\Http\Controllers\Resources\ShopGoodsController::class)
                ->parameters(['goods'=>'shopGoods'])
                ->names(['index'=>'goods']);

            Route::resource($adminPath . '/shop/item', \Elfcms\Shop\Http\Controllers\Resources\ShopItemController::class)
            ->parameters(['item'=>'shopItem'])
            ->names(['index'=>'item']);

            Route::resource($adminPath . '/shop/brand', \Elfcms\Shop\Http\Controllers\Resources\ShopBrandController::class)
            ->parameters(['brand'=>'shopBrand'])
            ->names(['index'=>'brand']);

            Route::resource($adminPath . '/shop/vendor', \Elfcms\Shop\Http\Controllers\Resources\ShopVendorController::class)
            ->parameters(['vendor'=>'shopVendor'])
            ->names(['index'=>'vendor']);

            Route::resource($adminPath . '/shop/tag', \Elfcms\Shop\Http\Controllers\Resources\ShopTagController::class)
            ->parameters(['tag'=>'shopTag'])
            ->names(['index'=>'tag']);

            Route::resource($adminPath . '/shop/tax', \Elfcms\Shop\Http\Controllers\Resources\ShopTaxController::class)
            ->parameters(['tax'=>'shopTax'])
            ->names(['index'=>'tax']);

            Route::resource($adminPath . '/shop/price-type', \Elfcms\Shop\Http\Controllers\Resources\ShopPriceTypeController::class)
            ->parameters(['price_type'=>'shopPriceType'])
            ->names(['index'=>'price_type']);

            Route::resource($adminPath . '/shop/price', \Elfcms\Shop\Http\Controllers\Resources\ShopPriceController::class)
            ->parameters(['price'=>'shopPrice'])
            ->names(['index'=>'price']);

            Route::resource($adminPath . '/shop/discount', \Elfcms\Shop\Http\Controllers\Resources\ShopDiscountController::class)
            ->parameters(['discount'=>'shopDiscount'])
            ->names(['index'=>'discount']);

            Route::resource($adminPath . '/shop/cart', \Elfcms\Shop\Http\Controllers\Resources\ShopCartController::class)
            ->parameters(['cart'=>'shopCart'])
            ->names(['index'=>'cart']);

            Route::resource($adminPath . '/shop/order-status', \Elfcms\Shop\Http\Controllers\Resources\ShopOrderStatusController::class)
            ->parameters(['order_status'=>'shopOrderStatus'])
            ->names(['index'=>'order_status']);

            Route::resource($adminPath . '/shop/order', \Elfcms\Shop\Http\Controllers\Resources\ShopOrderController::class)
            ->parameters(['order'=>'shopOrder'])
            ->names(['index'=>'order']);

            Route::resource($adminPath . '/shop/invoice-status', \Elfcms\Shop\Http\Controllers\Resources\ShopInvoiceStatusController::class)
            ->parameters(['invoice_status'=>'shopInvoiceStatus'])
            ->names(['index'=>'invoice_status']);

            Route::resource($adminPath . '/shop/invoice', \Elfcms\Shop\Http\Controllers\Resources\ShopInvoiceController::class)
            ->parameters(['invoice'=>'shopInvoice'])
            ->names(['index'=>'invoice']);

            Route::resource($adminPath . '/shop/payment', \Elfcms\Shop\Http\Controllers\Resources\ShopPaymentController::class)
            ->parameters(['payment'=>'shopPayment'])
            ->names(['index'=>'payment']);

            Route::resource($adminPath . '/shop/delivery', \Elfcms\Shop\Http\Controllers\Resources\ShopDeliveryController::class)
            ->parameters(['delivery'=>'shopDelivery'])
            ->names(['index'=>'delivery']);

            /* ---- */

            Route::resource($adminPath . '/shop/unit', \Elfcms\Shop\Http\Controllers\Resources\ShopUnitController::class)
            ->parameters(['unit'=>'shopUnit'])
            ->names(['index'=>'unit']);

            Route::resource($adminPath . '/shop/parameter', \Elfcms\Shop\Http\Controllers\Resources\ShopItemParameterController::class)
            ->parameters(['parameter'=>'shopItemParameter'])
            ->names(['index'=>'parameter']);

            Route::resource($adminPath . '/shop/property', \Elfcms\Shop\Http\Controllers\Resources\ShopItemPropertyController::class)
            ->parameters(['property'=>'shopItemProperty'])
            ->names(['index'=>'property']);

            Route::resource($adminPath . '/shop/currency', \Elfcms\Shop\Http\Controllers\Resources\ShopCurrencyController::class)
            ->parameters(['currency'=>'shopCurrency'])
            ->names(['index'=>'currency']);

            Route::get($adminPath . '/shop/ajax/csrf', [\Elfcms\Shop\Http\Controllers\AdminController::class, 'csrf'])->name('ajax.csrf');

            Route::get($adminPath . '/shop/ajax/unit/list/{byId?}', [\Elfcms\Shop\Http\Controllers\ShopUnitController::class, 'list'])->name('ajax.unit.list');
            Route::get($adminPath . '/shop/ajax/unit/empty-item', [\Elfcms\Shop\Http\Controllers\ShopUnitController::class, 'emptyItem'])->name('ajax.unit.empty-item');
            Route::post($adminPath . '/shop/ajax/unit/fullsave', [\Elfcms\Shop\Http\Controllers\ShopUnitController::class, 'save'])->name('ajax.unit.fullsave');

            Route::get($adminPath . '/shop/ajax/parameter/list/{byId?}', [\Elfcms\Shop\Http\Controllers\ShopParameterController::class, 'list'])->name('ajax.parameter.list');
            Route::get($adminPath . '/shop/ajax/parameter/empty-parameter', [\Elfcms\Shop\Http\Controllers\ShopParameterController::class, 'emptyItem'])->name('ajax.parameter.empty-item');
            Route::post($adminPath . '/shop/ajax/parameter/fullsave', [\Elfcms\Shop\Http\Controllers\ShopParameterController::class, 'save'])->name('ajax.parameter.fullsave');

            Route::get($adminPath . '/shop/ajax/property/list/{byId?}', [\Elfcms\Shop\Http\Controllers\ShopPropertyController::class, 'list'])->name('ajax.property.list');
            Route::get($adminPath . '/shop/ajax/property/empty-property', [\Elfcms\Shop\Http\Controllers\ShopPropertyController::class, 'emptyItem'])->name('ajax.property.empty-item');
            Route::post($adminPath . '/shop/ajax/property/fullsave', [\Elfcms\Shop\Http\Controllers\ShopPropertyController::class, 'save'])->name('ajax.property.fullsave');

            Route::get($adminPath . '/shop/ajax/currency/list/{byId?}', [\Elfcms\Shop\Http\Controllers\ShopCurrencyController::class, 'list'])->name('ajax.currency.list');
            Route::get($adminPath . '/shop/ajax/currency/empty-currency', [\Elfcms\Shop\Http\Controllers\ShopCurrencyController::class, 'emptyItem'])->name('ajax.currency.empty-item');
            Route::post($adminPath . '/shop/ajax/currency/fullsave', [\Elfcms\Shop\Http\Controllers\ShopCurrencyController::class, 'save'])->name('ajax.currency.fullsave');

            //Route::resource($adminPath . '/shop/coupon', \Elfcms\Shop\Http\Controllers\Resources\ShopCouponController::class);
        });
        /* Route::get($adminPath . '/ajax/json/simplebox/datatypes',function(Request $request){
            $result = [];
            if ($request->ajax()) {
                $result = SimpleboxDataType::all()->toArray();
                $result = json_encode($result);
            }
            return $result;
        }); */

    });

});
