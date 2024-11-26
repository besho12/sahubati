<?php

use Illuminate\Support\Facades\Route;

Route::get('shippingareas', [
    'as' => 'admin.shippingareas.index',
    'uses' => 'PageController@index',
    'middleware' => 'can:admin.shippingareas.index',
]);

Route::get('shippingareas/index/table', [
    'as' => 'admin.shippingareas.table',
    'uses' => 'PageController@table',
    'middleware' => 'can:admin.shippingareas.index',
]);

Route::get('shippingareas/create', [
    'as' => 'admin.shippingareas.create',
    'uses' => 'PageController@create',
    'middleware' => 'can:admin.shippingareas.create',
]);

Route::post('shippingareas', [
    'as' => 'admin.shippingareas.store',
    'uses' => 'PageController@store',
    'middleware' => 'can:admin.shippingareas.create',
]);

Route::get('shippingareas/{id}/edit', [
    'as' => 'admin.shippingareas.edit',
    'uses' => 'PageController@edit',
    'middleware' => 'can:admin.shippingareas.edit',
]);

Route::put('shippingareas/{id}/edit', [
    'as' => 'admin.shippingareas.update',
    'uses' => 'PageController@update',
    'middleware' => 'can:admin.shippingareas.edit',
]);

Route::delete('shippingareas/{ids?}', [
    'as' => 'admin.shippingareas.destroy',
    'uses' => 'PageController@destroy',
    'middleware' => 'can:admin.shippingareas.destroy',
]);
