<?php

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

Route::middleware('auth.apitoken')->group(function () {
    Route::post('/{user}/send', [\App\Http\Controllers\EmailController::class, 'send'])->name('email.send');
    Route::get('/{user}/list', [\App\Http\Controllers\EmailController::class, 'list'])->name('email.list');
});
