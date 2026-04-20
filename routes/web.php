<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContratoController;

Route::get('/', [MainController::class, 'index']);

// auth routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/loginSubmit', [AuthController::class, 'loginSubmit'])->name('loginSubmit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', function () {
    return view('dashboard.home');
})->name('home');

Route::post('/teste', [MainController::class, 'teste'])->name('teste');

Route::get('/contratos', [ContratoController::class, 'listaContratos'])->name('contratos');

Route::get('/contrato/{id}', [ContratoController::class, 'visualizaContrato'])->name('contrato.visualizar');

Route::get('/contrato/{id}/editar', [ContratoController::class, 'visualizaContrato'])->name('contrato.editar');

Route::get('/cardapio', function (){
    return view('dashboard.gerenciaCardapio');
})->name('cardapio');
