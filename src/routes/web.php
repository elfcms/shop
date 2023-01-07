<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$adminPath = Config::get('elfcms.basic.admin_path') ?? '/admin';

Route::group(['middleware'=>['web','cookie','start']],function() use ($adminPath) {

    Route::name('admin.')->middleware('admin')->group(function() use ($adminPath) {

        Route::name('shop.')->group(function() use ($adminPath) {
            Route::get($adminPath . '/shop', [\Elfcms\Shop\Http\Controllers\AdminController::class,'shop'])->name('index');
            Route::resource($adminPath . '/shop/category', Elfcms\Shop\Http\Controllers\Resources\ShopCategoryController::class);
            Route::resource($adminPath . '/shop/goods', Elfcms\Shop\Http\Controllers\Resources\ShopGoodsController::class);
            Route::resource($adminPath . '/shop/item', Elfcms\Shop\Http\Controllers\Resources\ShopItemController::class);
            Route::resource($adminPath . '/shop/brand', Elfcms\Shop\Http\Controllers\Resources\ShopBrandController::class);
            Route::resource($adminPath . '/shop/vendor', Elfcms\Shop\Http\Controllers\Resources\ShopVendorController::class);
            Route::resource($adminPath . '/shop/tag', Elfcms\Shop\Http\Controllers\Resources\ShopTagController::class);
            Route::resource($adminPath . '/shop/currency', Elfcms\Shop\Http\Controllers\Resources\ShopCurrencyController::class);
            Route::resource($adminPath . '/shop/tax', Elfcms\Shop\Http\Controllers\Resources\ShopTaxController::class);
            Route::resource($adminPath . '/shop/price-type', Elfcms\Shop\Http\Controllers\Resources\ShopPriceTypeController::class);
            Route::resource($adminPath . '/shop/price', Elfcms\Shop\Http\Controllers\Resources\ShopPriceController::class);
            Route::resource($adminPath . '/shop/discount', Elfcms\Shop\Http\Controllers\Resources\ShopDiscountController::class);
            Route::resource($adminPath . '/shop/cart', Elfcms\Shop\Http\Controllers\Resources\ShopCartController::class);
            Route::resource($adminPath . '/shop/order-status', Elfcms\Shop\Http\Controllers\Resources\ShopOrderStatusController::class);
            Route::resource($adminPath . '/shop/order', Elfcms\Shop\Http\Controllers\Resources\ShopOrderController::class);
            Route::resource($adminPath . '/shop/invoice-status', Elfcms\Shop\Http\Controllers\Resources\ShopInvoiceStatusController::class);
            Route::resource($adminPath . '/shop/invoice', Elfcms\Shop\Http\Controllers\Resources\ShopInvoiceController::class);
            Route::resource($adminPath . '/shop/payment', Elfcms\Shop\Http\Controllers\Resources\ShopPaymentController::class);
            Route::resource($adminPath . '/shop/delivery', Elfcms\Shop\Http\Controllers\Resources\ShopDeliveryController::class);
            Route::resource($adminPath . '/shop/coupon', Elfcms\Shop\Http\Controllers\Resources\ShopCouponController::class);
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
