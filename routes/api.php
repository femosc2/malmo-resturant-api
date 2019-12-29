<?php

use App\Resturant;
use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/resturants', function() {
    $resturants = Resturant::all();
    foreach($resturants as $resturant) {
        return $resturant->name;
    }
});


Route::middleware('auth:api')->get('/resturants', function (Request $request) {
    return $request->$resturants = Resturant::all();
    foreach($resturants as $resturant) {
        return $resturant->name;
    };
});

