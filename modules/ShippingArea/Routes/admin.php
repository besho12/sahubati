<?php

use Illuminate\Support\Facades\Route;

// dd('ddddddd');
Route::get('shippingareas', [
    'as' => 'admin.shipping_areas.index',
    'uses' => 'ShippingAreaController@index',
    'middleware' => 'can:admin.shipping_areas.index',
]);

Route::get('shippingareas/index/table', [
    'as' => 'admin.shipping_areas.table',
    'uses' => 'ShippingAreaController@table',
    'middleware' => 'can:admin.shipping_areas.index',
]);

Route::get('shippingareas/create', [
    'as' => 'admin.shipping_areas.create',
    'uses' => 'ShippingAreaController@create',
    'middleware' => 'can:admin.shipping_areas.create',
]);

Route::post('shippingareas', [
    'as' => 'admin.shipping_areas.store',
    'uses' => 'ShippingAreaController@store',
    'middleware' => 'can:admin.shipping_areas.create',
]);

Route::get('shippingareas/{id}/edit', [
    'as' => 'admin.shipping_areas.edit',
    'uses' => 'ShippingAreaController@edit',
    'middleware' => 'can:admin.shipping_areas.edit',
]);

Route::put('shippingareas/{id}/edit', [
    'as' => 'admin.shipping_areas.update',
    'uses' => 'ShippingAreaController@update',
    'middleware' => 'can:admin.shipping_areas.edit',
]);

Route::delete('shippingareas/{ids?}', [
    'as' => 'admin.shipping_areas.destroy',
    'uses' => 'ShippingAreaController@destroy',
    'middleware' => 'can:admin.shipping_areas.destroy',
]);
