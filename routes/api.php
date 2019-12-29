<?php

use App\Resturant;
use Illuminate\Http\Request;

Route::get('/resturants', 'ResturantsController@index');
Route::get('/resturants/{id}', 'ResturantsController@show');
Route::post('/resturants/new', 'ResturantsController@create');
Route::post('/items/new', 'ItemsController@create');
Route::get('/items/{id}', 'ItemsController@show');
Route::get('/items', 'ItemsController@index');
