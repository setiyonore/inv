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

Auth::routes(['register' => true]);

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['prefix'=>'customers'],function(){
   Route::get('/','CustomerController@index')->name('customers.index');
   Route::get('edit/{id}','CustomerController@edit')->name('customer.edit');
   Route::post('store','CustomerController@store')->name('customers.store');
   Route::get('destroy/{id}','CustomerController@destroy')->name('customers.delete');
   Route::get('search','CustomerController@search')->name('customers.search');
});

Route::group(['prefix'=>'typereferences'],function(){
   Route::get('/','TypeReferencesController@index')->name('typereferences.index');
   Route::get('edit/{id}','TypeReferencesController@edit')->name('typereferences.edit');
   Route::post('store','TypeReferencesController@store')->name('typereferences.store');
   Route::get('destroy/{id}','TypeReferencesController@destroy')->name('typereferences.delete');
   Route::get('search','TypeReferencesController@search')->name('typereferences.search');
});

Route::group(['prefix'=>'employees'],function (){
    Route::get('/','EmployeesController@index')->name('employees.index');
    Route::get('/getDivision','EmployeesController@getDivision')->name('employees.getDivison');
    Route::post('/store','EmployeesController@store')->name('employees.store');
    Route::get('/edit/{id}','EmployeesController@Edit')->name('employees.edit');
    Route::get('/destroy/{id}','EmployeesController@destroy')->name('employees.destroy');
    Route::get('/search','EmployeesController@search')->name('employees.search');
});

Route::group(['prefix'=>'references'],function (){
    Route::get('/','ReferencesController@index')->name('references.index');
    Route::post('/filter','ReferencesController@filter')->name('references.filter');
    Route::get('/edit/{id}','ReferencesController@edit')->name('references.edit');
    Route::get('getTypeReference', 'ReferencesController@getTypeReference')->name('references.getTypeReference');
});

