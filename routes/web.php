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
//customer
Route::group(['prefix'=>'customers'],function(){
   Route::get('/','CustomerController@index')->name('customers.index');
   Route::get('edit/{id}','CustomerController@edit')->name('customer.edit');
   Route::post('store','CustomerController@store')->name('customers.store');
   Route::get('destroy/{id}','CustomerController@destroy')->name('customers.delete');
   Route::get('search','CustomerController@search')->name('customers.search');
});

//tipe referensi
Route::group(['prefix'=>'typereferences'],function(){
   Route::get('/','TypeReferencesController@index')->name('typereferences.index');
   Route::get('edit/{id}','TypeReferencesController@edit')->name('typereferences.edit');
   Route::post('store','TypeReferencesController@store')->name('typereferences.store');
   Route::get('destroy/{id}','TypeReferencesController@destroy')->name('typereferences.delete');
   Route::get('search','TypeReferencesController@search')->name('typereferences.search');
});
//pegawai
Route::group(['prefix'=>'employees'],function (){
    Route::get('/','EmployeesController@index')->name('employees.index');
    Route::get('/getDivision','EmployeesController@getDivision')->name('employees.getDivison');
    Route::post('/store','EmployeesController@store')->name('employees.store');
    Route::get('/edit/{id}','EmployeesController@Edit')->name('employees.edit');
    Route::get('/destroy/{id}','EmployeesController@destroy')->name('employees.destroy');
    Route::get('/search','EmployeesController@search')->name('employees.search');
});
//paket
Route::group(['prefix'=>'package'],function (){
    Route::get('/','PackageController@index')->name('package.index');
    Route::post('/store','PackageController@store')->name('package.store');
    Route::post('/storeBenefit','PackageController@storeBenefit')->name('package.storeBenefit');
    Route::post('/storeFormatNaskah','PackageController@storeFormatNaskah')
        ->name('package.storeFormatNaskah');
    Route::get('edit/{id}','PackageController@edit')->name('package.edit');
    Route::get('destroy/{id}','PackageController@destroy')->name('package.destroy');
    Route::get('search','PackageController@search')->name('package.search');
    Route::get('detil/{id}','PackageController@detil')->name('package.detil');
    Route::get('destroyBenefit/{id}','PackageController@destroyBenefit')->name('package.dbenefit');
    Route::get('destroyFormat/{id}','PackageController@destroyFormatNaskah')->name('package.dformat');
    Route::get('getBenefitReference','PackageController@getBenefitReference')->name('package.benefitRef');
});

//referensi
Route::group(['prefix'=>'references'],function (){
    Route::post('/store','ReferencesController@store')->name('references.store');
    Route::get('/','ReferencesController@index')->name('references.index');
    Route::get('/destroy/{id}','ReferencesController@destroy')->name('references.destroy');
    Route::post('/filter','ReferencesController@filter')->name('references.filter');
    Route::get('/edit/{id}','ReferencesController@edit')->name('references.edit');
    Route::get('getTypeReference', 'ReferencesController@getTypeReference')->name('references.getTypeReference');
});

//no rekening
Route::group(['prefix'=>'norek'],function (){
    Route::get('/','MstNoRekeningController@index')->name('norek.index');
    Route::post('store','MstNoRekeningController@store')->name('norek.store');
    Route::get('/edit/{id}','MstNoRekeningController@edit')->name('norek.edit');
    Route::get('getBankReference','MstNoRekeningController@getBankReferensi')
        ->name('norek.getBankReferensi');
    Route::get('destroy/{id}','MstNoRekeningController@destroy')->name('norek.destroy');
    Route::post('search','MstNoRekeningController@search')->name('norek.search');

});

