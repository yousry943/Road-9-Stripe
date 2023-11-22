<?php

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


    Route::post('/register', [App\Http\Controllers\RegisterController::class, 'register']);

     Route::post('/login', [App\Http\Controllers\LoginController::class, 'login']);
     Route::get('/logout', [App\Http\Controllers\LoginController::class, 'logout']);



     Route::group(['prefix' => 'admin', 'middleware' => ['api', 'jwt.auth','checkAdmin']], function () {
         Route::get('/transactions', [App\Http\Controllers\Admin\TransactionController::class, 'index']);
        Route::get('/transactions/{id}', [App\Http\Controllers\Admin\TransactionController::class, 'show']);
        Route::post('/transactions', [App\Http\Controllers\Admin\TransactionController::class, 'create']);
        Route::post('/transactions/payments', [App\Http\Controllers\Admin\TransactionController::class, 'recordPayment']);
        Route::put('/transactions/{id}', [App\Http\Controllers\Admin\TransactionController::class, 'update']);
        Route::delete('/transactions/{id}', [App\Http\Controllers\Admin\TransactionController::class, 'destroy']);

        Route::get('/report/{startDate}/{endDate}', [App\Http\Controllers\Admin\AdminReportController::class, 'report']);


    });


    Route::group(['middleware' => ['api', 'jwt.auth','checkCustomer']], function () {
        Route::get('/customer-transactions', [App\Http\Controllers\TransactionCustomerController::class, 'index'])->name('transactions.index');
        Route::get('/customer-transactions/{transaction}', [App\Http\Controllers\TransactionCustomerController::class, 'show'])->name('transactions.show');


        Route::post('/create-customer', [App\Http\Controllers\Stripe\PaymentController::class, 'createCustomer']);

        Route::post('/chargeUser', [App\Http\Controllers\Stripe\PaymentController::class, 'chargeUser']);
        Route::post('/saveCard', [App\Http\Controllers\Stripe\PaymentController::class, 'saveCard']);

        Route::post('/generateCardToken', [App\Http\Controllers\Stripe\PaymentController::class, 'generateCardToken']);

});

/*

customer

eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzAwMjM5Mzg2LCJleHAiOjE3MDAyNDI5ODYsIm5iZiI6MTcwMDIzOTM4NiwianRpIjoiV3czWVBEVGJLWlJUQmgySCIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.9nw4G-LKWSe8dKPID-dSVQVB9Kki-K-uNydNEDE6HvQ
*/

/*

admin
eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzAwMjQwOTY3LCJleHAiOjE3MDAyNDQ1NjcsIm5iZiI6MTcwMDI0MDk2NywianRpIjoiREdZZWRoSXdPUXJNNEVQTyIsInN1YiI6IjIiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.ahRqIkA5mHV8A7SIeDvILiXZkENT5Ffv7Hm8eeM39yo
*/
