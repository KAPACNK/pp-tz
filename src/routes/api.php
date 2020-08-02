<?php

use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::apiResource('/transaction', 'TransactionController');

Route::post('users', function (Request $request) {
    $user = new User();
    return $user->store($request);
});

Route::post('transactions', function (Request $request) {
    $transaction = new Transaction();
    return $transaction->store($request);
});

// Route::get('transaction/user', 'TransactionController@user');

Route::get('transactions/{id}/{page?}/{sort?}', function ($user_id, $page = 1, $sort = NULL) {
    $transactions = new Transaction();
    return $transactions->getTransactionsByUser($user_id, $page, $sort);
});

Route::get('transactions-group/{date}/{page?}', function ($page = 1) {
    $transactions = new Transaction();
    return $transactions->getTransactionsGroup($page);
});
