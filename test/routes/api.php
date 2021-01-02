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

Route::middleware('auth:api')->group(function () {
    // our routes to be protected will go in here
    Route::post('/logout', 'Auth\ApiAuthController@logout')->name('logout.api');

   // Route::get('/articles', 'ArticlesController@index')->name('articles');

    Route::get('/articles','ArticlesController@index')->middleware('api.admin');

});

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('/login', 'Auth\ApiAuthController@login')->name('login.api');
    Route::post('/register','Auth\ApiAuthController@register')->name('register.api');

    Route::post('/register','Auth\ApiAuthController@register')->name('register.api');

    Route::post('/products/add', 'ProductsController@store')->name('products.api');

    Route::post('/products/viewProducts', 'ProductsController@viewProducts')->name('products.api');


});



Route::get('/autherror', 'ProductsController@store')->name('autherror');

