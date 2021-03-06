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


Route::get('/', 'TransactionController@index');
Route::get('list', 'TransactionController@listTransactions');
//Route::get('apiBanks', 'TransactionController@getBankList');
//Route::get('transactionInfo/{id}', 'TransactionController@getTransactionInfo');
Route::post('transaction', 'TransactionController@sendTransaction');
Route::any('resultTransaction', 'TransactionController@resultTransaction');


