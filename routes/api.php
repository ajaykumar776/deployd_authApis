<?php

use App\Http\Middleware\AuthCheck;
use App\Http\Middleware\UserAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('/login', 'api\AuthApi@login')->name('login_api');
    Route::post('/register', 'api\AuthApi@register')->name('register_api');
});
Route::middleware([UserAuthMiddleware::class])->group(function () {
    Route::prefix('profile')->group(function () {
        Route::post('/get', 'api\AuthApi@get')->name('profile_get_api')->middleware(UserAuthMiddleware::class);
    });
});
