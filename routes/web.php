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
})->name('listarAlunos');

Route::get('/criaracessos', function (){
    return view('dashboard.criaracessos');
})->name('criarAcessos');

Route::get('/criarmodelos', function (){
    return view('dashboard.criarmodelos');
})->name('criarModelos');

Route::post('/teste', [MainController::class, 'teste'])->name('teste');
