<?php

use Illuminate\Http\Request;

//Tokens
Route::get('/tokens/authorized', 'ApiTokenController@create_authorized_token');
Route::get('/tokens/superuser', 'ApiTokenController@create_superuser_token');


//Resturants
Route::delete('/resturants/delete/{id}', 'ResturantsController@destroy');
Route::get('/resturants', 'ResturantsController@index');
Route::get('/resturants/{id}', 'ResturantsController@show');
Route::get('/resturants/by/name/{name}', 'ResturantsController@show_by_name');
Route::get('/resturants/by/location/{location}', 'ResturantsController@show_by_location');
Route::post('/resturants/new', 'ResturantsController@create');
Route::put('/resturants/report/{id}', 'ResturantsController@report');
Route::put('/resturants/unreport/{id}', 'ResturantsController@unreport');


// Items
Route::delete('/items/delete/{id}', 'ItemsController@destroy');
Route::post('/items/new', 'ItemsController@create');
Route::put('/items/report/{id}', 'ItemsController@report');
Route::put('/items/unreport/{id}', 'ItemsController@unreport');
Route::get('/items/{id}', 'ItemsController@show');
Route::get('/items/by/name/{name}', 'ItemsController@show_by_name');
Route::get('/items/by/type/{name}', 'ItemsController@show_by_type');
Route::get('/items', 'ItemsController@index');


// Item Reviews
Route::delete('/itemreviews/delete/{id}', 'ItemReviewsController@destroy');
Route::post('/itemreviews/new', 'ItemReviewsController@create');
Route::get('/itemreviews', 'ItemReviewsController@index');
Route::get('/itemreviews/{id}', 'ItemReviewsController@show');
Route::put('/itemreviews/report/{id}', 'ItemReviewsController@report');
Route::put('/itemreviews/unreport/{id}', 'ItemReviewsController@unreport');


// Resturant Reviews
Route::delete('/resturantreviews/delete/{id}', 'ResturantReviewsController@destroy');
Route::post('/resturantreviews/new', 'ResturantReviewsController@create');
Route::get('/resturantreviews', 'ResturantReviewsController@index');
Route::get('/resturantreviews/{id}', 'ResturantReviewsController@show');
Route::put('/resturantreviews/report/{id}', 'ResturantReviewsController@report');
Route::put('/resturantreviews/unreport/{id}', 'ResturantReviewsController@unreport');


// Resturant Images
Route::delete('/resturantimages/delete/{id}', 'ResturantImagesController@destroy');
Route::post('/resturantimages/new', 'ResturantImagesController@create');
Route::get('/resturantimages', 'ResturantImagesController@index');
Route::get('/resturantimages/{id}', 'ResturantImagesController@show');
Route::put('/resturantimages/report/{id}', 'ResturantImagesController@report');
Route::put('/resturantimages/unreport/{id}', 'ResturantImagesController@unreport');


// Item Images
Route::delete('/itemimages/delete/{id}', 'ItemImagesController@destroy');
Route::post('/itemimages/new', 'ItemImagesController@create');
Route::get('/itemimages', 'ItemImagesController@index');
Route::get('/itemimages/{id}', 'ItemImagesController@show');
Route::put('/itemimages/report/{id}', 'ItemImagesController@report');
Route::put('/itemimages/unreport/{id}', 'ItemImagesController@unreport');


// Dummy Data
Route::post('/dummydata', 'DummyDataController@create');
