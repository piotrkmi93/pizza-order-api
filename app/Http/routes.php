<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');

Route::get('/', 'HomeController@index');

Route::auth();

Route::get('/orders', 'OrderController@getOrders');

Route::get('/toppings-edit', 'ToppingController@showToppings');

//Route::get('/register', 'HomeController@index');

Route::get('/toppings', 'ToppingController@getAllToppings');

Route::get('/topping/{id}', 'ToppingController@getToppingById');

Route::post('/add-topping', 'ToppingController@addTopping');

Route::delete('/topping/{id}', 'ToppingController@deleteTopping');

Route::post('/editTopping', 'ToppingController@editTopping');

Route::get('/addOrder', 'OrderController@addOrder');

Route::get('changeStatus', 'OrderController@changeStatus');

Route::delete('deleteOrder/{id}', 'OrderController@deleteOrder');