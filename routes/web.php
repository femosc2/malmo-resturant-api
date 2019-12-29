<?php

use App\Resturant;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('newresturant', function() {
    $resturant = new Resturant;

    $resturant->name = "name";
    $resturant->location = "location";
    $resturant->save();
});

