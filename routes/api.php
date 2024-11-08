<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampanaController;
use App\Http\Middleware\VerifyJwtToken;

Route::prefix('auth')->group(function () {
    Route::middleware([VerifyJwtToken::class])->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
});
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});

    Route::prefix('campanas')->group(function () {
        Route::post('/', [CampanaController::class, 'agregarCampana']);
        Route::get('/{id}', [CampanaController::class, 'obtenerCampana']);
        Route::delete('/id={id}', [CampanaController::class, 'sacarCampana']);
    });

