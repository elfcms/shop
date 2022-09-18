<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware'=>['web','cookie','start']],function(){

    Route::name('admin.')->middleware('admin')->group(function(){

        Route::name('shop.')->group(function(){
            Route::get('/admin/shop', [\Elfcms\Shop\Http\Controllers\AdminController::class,'shop'])->name('index');
        });
        /* Route::get('/admin/ajax/json/simplebox/datatypes',function(Request $request){
            $result = [];
            if ($request->ajax()) {
                $result = SimpleboxDataType::all()->toArray();
                $result = json_encode($result);
            }
            return $result;
        }); */

    });

});
