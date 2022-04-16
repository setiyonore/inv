<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['prefix'=>'customers'],function(){
   Route::get('/','CustomerController@index')->name('customers.index');
   Route::get('edit/{id}','CustomerController@edit')->name('customer.edit');
   Route::post('store','CustomerController@store')->name('customers.store');
   Route::get('destroy/{id}','CustomerController@destroy')->name('customers.delete');
   Route::get('search','CustomerController@search')->name('customers.search');
});
