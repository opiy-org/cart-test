<?php

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


//---Products
//Guest view
Route::group(['prefix' => 'products'], function () {
    Route::get('/', 'Api\ProductsController@index');
    Route::get('/{id}', 'Api\ProductsController@view')->where(['id' => '[0-9]+']);

    //Admin view.
    // //TODO: add admin auth middleware
    Route::post('/', 'Api\ProductsController@create');
    Route::put('/{id}', 'Api\ProductsController@update')->where(['id' => '[0-9]+']);
    Route::delete('/{id}', 'Api\ProductsController@delete')->where(['id' => '[0-9]+']);
});

//---Cart
//Guest view
Route::group(['prefix' => 'cart', 'middleware' => ['startsession', 'cookiestoken']], function () {
    Route::get('/', 'Api\CartController@index');
    Route::post('/', 'Api\CartController@add');
    Route::delete('/{id}', 'Api\CartController@delete')->where(['id' => '[0-9]+']);
});








