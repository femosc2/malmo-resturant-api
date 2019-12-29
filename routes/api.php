<?php

use App\Resturant;
use Illuminate\Http\Request;

Route::get('/resturants', 'Resturants@index');
Route::post('/resturants/new', 'Resturants@create');



