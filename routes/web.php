<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;

Route::get('/', [MainController::class, 'index']);

// auth routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/loginSubmit', [AuthController::class, 'loginSubmit'])->name('loginSubmit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', function () {
    return view('dashboard.home');
})->name('home');

Route::post('/teste', [MainController::class, 'teste'])->name('teste');

Route::get('/contratos', function (){
    return view('dashboard.listaContratos');
})->name('contratos');

Route::get('/contrato/{id?}', function (){
    return view('dashboard.contrato');
})->name('contrato');

Route::get('/cardapio', function (){
    return view('dashboard.gerenciaCardapio');
})->name('cardapio');
