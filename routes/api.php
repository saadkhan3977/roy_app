<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\CodeCheckController;
use App\Http\Controllers\API\ResetPasswordController;
use App\Http\Controllers\API\ChangePasswordController;
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


Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
	Route::get('noauth', 'noauth')->name('noauth');
});

//Route::get('noauth', [\App\Http\ControllersRegisterController::class, 'noauth']);

Route::group(['middleware' => ['api','auth:sanctum'], 'prefix' => 'auth'], function () {
    Route::get('products', [App\Http\Controllers\API\ProductController::class,'index']);
    Route::get('product_detail/{id}', [App\Http\Controllers\API\ProductController::class,'detail_info']);
	Route::get('product_category/{id}', [App\Http\Controllers\API\ProductController::class,'product_by_categories']);
	Route::get('product_sub_category/{id}', [App\Http\Controllers\API\ProductController::class,'product_by_sub_categories']);
	Route::get('product_child_category/{id}', [App\Http\Controllers\API\ProductController::class,'product_by_child_categories']);
    Route::resource('carts', App\Http\Controllers\API\CartController::class);
    Route::post('order', [\App\Http\Controllers\API\OrderController::class, 'order']);
    Route::get('order/list', [\App\Http\Controllers\API\OrderController::class, 'order_list']);
    Route::get('category/list', [\App\Http\Controllers\API\ProductController::class, 'category_list']);
    Route::post('profile/update', [\App\Http\Controllers\API\UserController::class, 'profile_update']);
});

Route::post('password/code/check', [CodeCheckController::class,'code_check']);
Route::post('password/reset', [ResetPasswordController::class,'reset_password']);
Route::post('/password/email',  [ForgotPasswordController::class,'forgot_Password']);
Route::post('/changepassword', [ChangePasswordController::class,'change_password'])->middleware('auth:sanctum');

