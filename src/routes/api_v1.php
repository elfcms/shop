<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
$apiPath = '/api/v1';

Route::group(['middleware'=>['api_json']],function() use ($apiPath) {

    Route::get($apiPath . '/health',[Elfcms\Shop\Http\Controllers\Api\V1\ApiController::class, 'health']);
    Route::get($apiPath . '/version',[Elfcms\Shop\Http\Controllers\Api\V1\ApiController::class, 'version']);

    Route::group(['middleware'=>['api_auth']],function() use ($apiPath) {
        Route::get($apiPath . '/products',[Elfcms\Shop\Http\Controllers\Api\V1\ProductsController::class, 'getProducts']);
        Route::post($apiPath . '/products',[Elfcms\Shop\Http\Controllers\Api\V1\ProductsController::class, 'setProduct']);
        Route::get($apiPath . '/products/{id}',[Elfcms\Shop\Http\Controllers\Api\V1\ProductsController::class, 'getProduct']);
        Route::post($apiPath . '/products/{id}',[Elfcms\Shop\Http\Controllers\Api\V1\ItemsController::class, 'setNewItem']);

        Route::get($apiPath . '/items',[Elfcms\Shop\Http\Controllers\Api\V1\ItemsController::class, 'getItems']);
        Route::get($apiPath . '/items/{id}',[Elfcms\Shop\Http\Controllers\Api\V1\ItemsController::class, 'getItem']);


        Route::post($apiPath . '/category',[Elfcms\Shop\Http\Controllers\Api\V1\ProductsController::class, 'setNewCategory']);
    });

    Route::get($apiPath . '/{any}',[Elfcms\Shop\Http\Controllers\Api\V1\ApiController::class, 'pageNotFound'])->where('any', '.*');
});
