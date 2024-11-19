<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampanaController;
use App\Http\Controllers\ReciclajeController;
use App\Http\Controllers\PuntosAzulesController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']) ->name('login');

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});


Route::get('campanas', [CampanaController::class, 'obtenerCampanas']);
Route::post('/campanas/agregar', [CampanaController::class, 'agregarCampana']);
Route::post('/campanas/quitar', [CampanaController::class, 'quitarCampana']);
Route::get('/campanas/ver-ultima', [CampanaController::class, 'verUltimaCampana']);
Route::get('/campanas/tamano', [CampanaController::class, 'obtenerTamanoPila']);


Route::post('/reciclajes/agregar', [ReciclajeController::class, 'agregarReciclaje']);
Route::get('/reciclajes/procesar', [ReciclajeController::class, 'procesarCola']);
Route::get('/reciclajes/tamano', [ReciclajeController::class, 'obtenerTamanoCola']);


Route::post('/puntos-azules/agregar', [PuntosAzulesController::class, 'agregarPuntoAzul']);
Route::get('/puntos-azules/procesar', [PuntosAzulesController::class, 'procesarPuntosAzules']);

