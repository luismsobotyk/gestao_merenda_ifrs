<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\CardapioController;

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
Route::post('/contrato/{id}/empenho/salvar', [ContratoController::class, 'salvaEmpenho'])->name('empenho.salvar');
Route::post('/contrato/{id}/pedido/salvar', [ContratoController::class, 'salvaPedido'])->name('pedido.salvar');
Route::patch('/pedido/{id}/receber', [ContratoController::class, 'receberPedido'])->name('pedido.receber');
// Rotas para gestão de fornecedores
Route::get('/fornecedor/busca-cnpj', [FornecedorController::class, 'buscaPorCnpj'])->name('fornecedor.busca.cnpj');


// Rotas para cardápio
Route::get('/cardapio', [CardapioController::class, 'index'])->name('cardapio');
Route::get('/cardapio/novo', [CardapioController::class, 'create'])->name('cardapio.novo');
Route::post('/cardapio/salvar', [CardapioController::class, 'store'])->name('cardapio.salvar');
Route::get('/cardapio/{id}/editar', [CardapioController::class, 'edit'])->name('cardapio.editar');
Route::post('/cardapio/{id}/sync', [CardapioController::class, 'syncAll'])->name('cardapio.sync');

// Outras rotas
Route::get('/', [MainController::class, 'index']);
Route::get('/home', function () {
    return view('dashboard.home');
})->name('home');
Route::post('/teste', [MainController::class, 'teste'])->name('teste');
