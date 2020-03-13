<?php

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

Route::get('/cities', 'CitiesController@index')->name('cities');
Route::get('/cities/search', 'CitiesController@search')->name('search');
Route::get('/', 'CountriesController@index');
Route::get('/countries/{name}', 'CountriesController@show')->name('country.details');
Route::get('/countries/{name}/cities', 'CountriesController@cities')->name('country.cities');
Route::get('/countries/{country}/{city}', 'CitiesController@show')->name('city.details');
