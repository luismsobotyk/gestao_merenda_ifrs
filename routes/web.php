<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\FornecedorController;

// Rotas de autenticação
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/loginSubmit', [AuthController::class, 'loginSubmit'])->name('loginSubmit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas para gestão de contratos
Route::get('/contratos', [ContratoController::class, 'listaContratos'])->name('contratos');
Route::get('/contratos/criar', [ContratoController::class, 'criaContrato'])->name('contrato.criar');
Route::post('/contratos/criar/salvar', [ContratoController::class, 'salvaContrato'])->name('contrato.salvar');
Route::get('/contrato/{id}', [ContratoController::class, 'visualizaContrato'])->name('contrato.visualizar');
Route::get('/contrato/{id}/editar', [ContratoController::class, 'visualizaContrato'])->name('contrato.editar');

// Rotas para gestão de fornecedores
Route::get('/fornecedor/busca-cnpj', [FornecedorController::class, 'buscaPorCnpj'])->name('fornecedor.busca.cnpj');

// Outras rotas
Route::get('/', [MainController::class, 'index']);
Route::get('/home', function () {
    return view('dashboard.home');
})->name('home');
Route::post('/teste', [MainController::class, 'teste'])->name('teste');
Route::get('/cardapio', function (){
    return view('dashboard.gerenciaCardapio');
})->name('cardapio');
