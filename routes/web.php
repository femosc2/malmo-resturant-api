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

Route::get('/resturants', function() {
    $resturants = Resturant::all();

    foreach($resturants as $resturant) {
        return response()->json([
            'name' => $resturant->name,
            'location' => $resturant->location,
            'rating' => $resturant->rating,
        ]);
    }
});

