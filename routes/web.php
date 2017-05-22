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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

//Route::get('/', 'HomeController@index');

Route::group(['middleware'=>['auth']], function(){
	//
	Route::get('orders/report/ajax/{date1}/{date2}/{tipo?}/{cia?}/{status?}', ['as' => 'ajaxordersreport', 'uses' => 'OrdersController@ajaxReportOrder']);
	//Route::get('invoices/report/ajax/{date1}/{date2}/{status}', ['as' => 'ajaxinvoicesreport','uses' => 'InvoicesController@ajaxReportInvoice']);

	//
	//Route::get('invoices/report', ['as' => 'invoicesreport', 'uses' => 'InvoicesController@reportInvoice']);
	Route::get('orders/report', ['as' => 'ordersreport','uses' => 'OrdersController@reportOrder']);

});