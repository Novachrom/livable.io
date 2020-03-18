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
Route::get('/', 'CitiesController@index')->name('cities');
Route::get('/countries', 'CountriesController@index')->name('countries');
Route::get('/cities/search', 'CitiesController@search')->name('search');
Route::get('/countries/{name}', 'CountriesController@show')->name('country.details');
Route::get('/countries/{name}/cities', 'CountriesController@cities')->name('country.cities');
Route::get('/countries/{country}/{city}', 'CitiesController@show')->name('city.details');
Route::get('/currency-mode/set', 'CurrencyModeController@set')->name('currency-mode.set');

Auth::routes(['register' => false]);
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::resource('calculations', 'CalculationController');
});
