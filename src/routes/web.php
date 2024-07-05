<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$adminPath = config('elfcms.elfcms.admin_path') ?? '/admin';

Route::group(['middleware'=>['web','cookie']],function() use ($adminPath) {

    Route::name('admin.')->middleware('admin')->group(function() use ($adminPath) {

        /* Sellings */

        Route::name('selling.')->group(function() use ($adminPath) {

            //Route::get($adminPath . '/selling', [\Elfcms\Shop\Http\Controllers\SellingController::class,'index'])->name('index');

            Route::resource($adminPath . '/selling/customers', \Elfcms\Shop\Http\Controllers\Resources\ShopCustomerController::class)
            ->names(['index'=>'customers']);

            Route::resource($adminPath . '/selling/orders', \Elfcms\Shop\Http\Controllers\Resources\ShopOrderController::class)
            ->names(['index'=>'orders']);

            Route::post($adminPath . '/selling/orders/{order}/invoice', [\Elfcms\Shop\Http\Controllers\InvoiceController::class, 'set'])
            ->name('set-invoice');
            Route::post($adminPath . '/selling/invoice/{invoice}/cancel', [\Elfcms\Shop\Http\Controllers\InvoiceController::class, 'cancel'])
            ->name('cancel-invoice');
            Route::post($adminPath . '/selling/invoice/{invoice}/pay', [\Elfcms\Shop\Http\Controllers\InvoiceController::class, 'pay'])
            ->name('pay-invoice');

            /* Route::resource($adminPath . '/selling/carts', \Elfcms\Shop\Http\Controllers\Resources\ShopCartController::class)
            ->names(['index'=>'carts']); */

        });

        /* /Sellings */

        Route::name('shop.')->group(function() use ($adminPath) {
            Route::get($adminPath . '/shop', [\Elfcms\Shop\Http\Controllers\AdminController::class,'shop'])->name('index');


            Route::get($adminPath . '/shop/image-settings', [\Elfcms\Shop\Http\Controllers\ShopImageSettingController::class,'show'])->name('image-settings.show');
            Route::post($adminPath . '/shop/image-settings', [\Elfcms\Shop\Http\Controllers\ShopImageSettingController::class,'save'])->name('image-settings.save');

            Route::resource($adminPath . '/shop/category', \Elfcms\Shop\Http\Controllers\Resources\ShopCategoryController::class)
                ->parameters(['category'=>'shopCategory'])
                ->names(['index'=>'category']);

            Route::resource($adminPath . '/shop/product', \Elfcms\Shop\Http\Controllers\Resources\ShopProductController::class)
                ->parameters(['product'=>'shopProduct'])
                ->names(['index'=>'product']);

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

            /* Route::resource($adminPath . '/shop/invoice-status', \Elfcms\Shop\Http\Controllers\Resources\ShopInvoiceStatusController::class)
            ->parameters(['invoice_status'=>'shopInvoiceStatus'])
            ->names(['index'=>'invoice_status']);

            Route::resource($adminPath . '/shop/invoice', \Elfcms\Shop\Http\Controllers\Resources\ShopInvoiceController::class)
            ->parameters(['invoice'=>'shopInvoice'])
            ->names(['index'=>'invoice']); */

            /* Route::resource($adminPath . '/shop/payment-method', \Elfcms\Shop\Http\Controllers\Resources\ShopPaymentMethodController::class, ['parameters'=>['payment_method'=>'shopPaymentMethod']])
            ->names(['index'=>'payment-method']); */

            Route::resource($adminPath . '/shop/delivery', \Elfcms\Shop\Http\Controllers\Resources\ShopDeliveryController::class)
            ->parameters(['delivery'=>'shopDelivery'])
            ->names(['index'=>'delivery']);

            Route::resource($adminPath . '/shop/payment', \Elfcms\Shop\Http\Controllers\Resources\ShopPaymentMethodController::class)
            ->parameters(['payment'=>'shopPaymentMethod'])
            ->names(['index'=>'payment']);

            /* ---- */

            Route::resource($adminPath . '/shop/items/{item}/pictures', Elfcms\Shop\Http\Controllers\Resources\ShopItemPictureController::class)
            //->parameters(['items'=>'shopItem'])
            ->names([
                'index' => 'items.pictures',
                'create' => 'items.pictures.create',
                'edit' => 'items.pictures.edit',
                'store' => 'items.pictures.store',
                'show' => 'items.pictures.show',
                'edit' => 'items.pictures.edit',
                'update' => 'items.pictures.update',
                'destroy' => 'items.pictures.destroy',
            ]);
            Route::post($adminPath . '/shop/items/{item}/pictures/group', [\Elfcms\Shop\Http\Controllers\AdminController::class,'itemPictureGroupSave'])->name('items.pictures.groupSave');


            /* ---- */

            Route::resource($adminPath . '/shop/return-reason', \Elfcms\Shop\Http\Controllers\Resources\ShopReturnReasonController::class);
            //->parameters(['coupon'=>'shopCoupon'])
            //->names(['index'=>'coupon']);

            Route::resource($adminPath . '/shop/coupon', \Elfcms\Shop\Http\Controllers\Resources\ShopCouponController::class)
            ->parameters(['coupon'=>'shopCoupon'])
            ->names(['index'=>'coupon']);

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

            Route::resource($adminPath . '/shop/tax', \Elfcms\Shop\Http\Controllers\Resources\ShopTaxController::class)
            ->parameters(['tax'=>'shopTax'])
            ->names(['index'=>'tax']);

            Route::resource($adminPath . '/shop/price-type', \Elfcms\Shop\Http\Controllers\Resources\ShopPriceTypeController::class)
            ->parameters(['priceType'=>'shopPriceType'])
            ->names(['index'=>'price-type']);

            Route::get($adminPath . '/shop/color', [\Elfcms\Shop\Http\Controllers\ShopColorController::class,'index'])->name('color');


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

            Route::get($adminPath . '/shop/ajax/rreason/list/{byId?}', [\Elfcms\Shop\Http\Controllers\ShopReturnReasonController::class, 'list'])->name('ajax.rreason.list');
            Route::get($adminPath . '/shop/ajax/rreason/empty-rreasons', [\Elfcms\Shop\Http\Controllers\ShopReturnReasonController::class, 'emptyItem'])->name('ajax.rreason.empty-item');
            Route::post($adminPath . '/shop/ajax/rreason/fullsave', [\Elfcms\Shop\Http\Controllers\ShopReturnReasonController::class, 'save'])->name('ajax.rreason.fullsave');

            Route::get($adminPath . '/shop/ajax/vendor/list/{byId?}', [\Elfcms\Shop\Http\Controllers\ShopVendorController::class, 'list'])->name('ajax.vendor.list');
            Route::get($adminPath . '/shop/ajax/vendor/empty-vendor', [\Elfcms\Shop\Http\Controllers\ShopVendorController::class, 'emptyItem'])->name('ajax.vendor.empty-item');
            Route::post($adminPath . '/shop/ajax/vendor/fullsave', [\Elfcms\Shop\Http\Controllers\ShopVendorController::class, 'save'])->name('ajax.vendor.fullsave');

            /* Route::get($adminPath . '/shop/ajax/delivery/list/{byId?}', [\Elfcms\Shop\Http\Controllers\ShopDeliveryController::class, 'list'])->name('ajax.delivery.list');
            Route::get($adminPath . '/shop/ajax/delivery/empty-delivery', [\Elfcms\Shop\Http\Controllers\ShopDeliveryController::class, 'emptyItem'])->name('ajax.delivery.empty-item');
            Route::post($adminPath . '/shop/ajax/delivery/fullsave', [\Elfcms\Shop\Http\Controllers\ShopDeliveryController::class, 'save'])->name('ajax.delivery.fullsave'); */

            Route::get($adminPath . '/shop/ajax/color/list/{byId?}', [\Elfcms\Shop\Http\Controllers\ShopColorController::class, 'list'])->name('ajax.color.list');
            Route::get($adminPath . '/shop/ajax/color/empty-color', [\Elfcms\Shop\Http\Controllers\ShopColorController::class, 'emptyItem'])->name('ajax.color.empty-item');
            Route::post($adminPath . '/shop/ajax/color/fullsave', [\Elfcms\Shop\Http\Controllers\ShopColorController::class, 'save'])->name('ajax.color.fullsave');

            Route::get($adminPath . '/shop/ajax/tax/list/{byId?}', [\Elfcms\Shop\Http\Controllers\ShopTaxController::class, 'list'])->name('ajax.tax.list');
            Route::get($adminPath . '/shop/ajax/tax/empty-tax', [\Elfcms\Shop\Http\Controllers\ShopTaxController::class, 'emptyItem'])->name('ajax.tax.empty-item');
            Route::post($adminPath . '/shop/ajax/tax/fullsave', [\Elfcms\Shop\Http\Controllers\ShopTaxController::class, 'save'])->name('ajax.tax.fullsave');

            Route::get($adminPath . '/shop/ajax/price-type/list/{byId?}', [\Elfcms\Shop\Http\Controllers\ShopPriceTypeController::class, 'list'])->name('ajax.price-type.list');
            Route::get($adminPath . '/shop/ajax/price-type/empty-price-type', [\Elfcms\Shop\Http\Controllers\ShopPriceTypeController::class, 'emptyItem'])->name('ajax.price-type.empty-item');
            Route::post($adminPath . '/shop/ajax/price-type/fullsave', [\Elfcms\Shop\Http\Controllers\ShopPriceTypeController::class, 'save'])->name('ajax.price-type.fullsave');

            Route::get($adminPath . '/shop/ajax/itemprice/empty-price', [\Elfcms\Shop\Http\Controllers\ShopPriceController::class, 'emptyItem'])->name('ajax.itemprice.empty-item');


            Route::resource($adminPath . '/shop/receipts', \Elfcms\Shop\Http\Controllers\Resources\ShopReceiptController::class)
            //->parameters(['receipts'=>'shopReceipt'])
            ->names(['index'=>'receipts']);

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

    Route::get('/shop',\Elfcms\Shop\Http\Controllers\PublicController::class);

});
