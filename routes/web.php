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
Route::group(['prefix'=>'home'],function(){
    Route::get('/','HomeController@index')->name('home');
    Route::get('/getOrderPerMonth','HomeController@getOrderPerMonth')->name('home.orderPerMonth');
});
Route::group(['middleware' => ['auth']], function () {
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
        Route::get('getTypePackage','PackageController@getTipePaket')->name('package.getTipePaket');
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

//transaction
    Route::group(['prefix'=>'transaction'],function (){
        Route::get('/','TransactionController@index')->name('transaction.index');
        Route::post('/store','TransactionController@store')->name('transaction.store');
        Route::get('/edit/{id}','TransactionController@edit')->name('transaction.edit');
        Route::get('/getCustomerId/{id}','TransactionController@getCustomerId')
            ->name('transacton.getCustId');
        Route::get('destroy/{id}','TransactionController@destroy');
        Route::get('detail/{id}','TransactionController@detail')->name('transaction.detail');
        Route::post('search','TransactionController@search')->name('transaction.search');
        Route::get('searchCustomer/{keyword}','TransactionController@searchCustomer')
            ->name('transaction.searchCust');
        Route::get('getPackage','TransactionController@getPackage')->name('transaction.getPackage');
        Route::get('getPricePackage/{id}','TransactionController@getPricePackage')
            ->name('transaction.getPricePackage');
        Route::get('getTypeOfPayment','TransactionController@getTypeOfPayment')
            ->name('transaction.getTop');
        Route::get('getTypeTransaction','TransactionController@getTypeTransaction')
            ->name('transaction.getTypeTransaction');
        Route::get('getNorek','TransactionController@getNoRekening')
            ->name('transaction.getNorek');
        Route::get('getDetilInvoice/{id}','TransactionController@detilInvoice')
            ->name('transaction.getDetilInvoice');
        Route::get('exportInvoice/{id}','TransactionController@exportInvoice')
            ->name('transaction.exportInvoice');
        Route::get('updateStatusInvoice/{id}','TransactionController@updateStatusInvoice')
            ->name('transaction.updateStatusInvoice');
        Route::get('getManpowerTransaction/{id}','TransactionController@getManpowerTransaction')
            ->name('transaction.getManpowerTr');
        Route::post('storeManpower','TransactionController@storeManpower')
            ->name('transaction.storeMapower');
        Route::get('destroyManpower/{id}','TransactionController@destroyManpower')
            ->name('transaction.destroyMan');
    });

//report / rekap
    Route::group(['prefix'=>'report'],function () {
        Route::get('/', 'ReportController@index')->name('report.index');
        Route::get('/getAmount','ReportController@getAmount')
            ->name('report.getAmount');
        Route::post('/search','ReportController@search')
            ->name('report.search');
        Route::post('/searchAmount','ReportController@getAmountFilter')
            ->name('report.searchAmount');
        Route::get('exportReport','ReportController@ExportExcel')
            ->name('report.excel');
    });

//task list
    Route::group(['prefix'=>'task'],function (){
        Route::get('/','TaskListController@index')
            ->name('task.index');
        Route::get('getStatus','TaskListController@getStatus')
            ->name('task.getStatus');
        Route::post('update','TaskListController@update')
            ->name('task.update');
        Route::post('filter','TaskListController@search')
            ->name('task.filter');
    });
//roles
    Route::group(['prefix' => 'roles'],function (){
        Route::get('/','RolesController@index')
            ->name('roles.index');
        Route::post('/store','RolesController@store')
            ->name('roles.store');
        Route::get('/getPermission/{id}','RolesController@getPermission')
            ->name('roles.getPermission');
    });
//users
    Route::group(['prefix'=>'users'],function (){
        Route::get('/','UserController@index')
            ->name('users.index');
        Route::get('/getRoles','UserController@getRoles')
            ->name('users.getRoles');
        Route::get('/getEmployee','UserController@getEmployee')
            ->name('users.getEmployee');
        Route::post('/store','UserController@store')
            ->name('users.store');
    });
});

