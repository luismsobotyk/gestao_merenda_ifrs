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
Route::post('/cardapio/{id}/horario', [\App\Http\Controllers\CardapioController::class, 'storeHorario'])->name('cardapio.horario.salvar');
Route::delete('/cardapio/horario/{id}/excluir', [CardapioController::class, 'destroyHorario'])->name('cardapio.horario.excluir');
Route::post('/cardapio/{id}/item-padrao', [CardapioController::class, 'storeItemPadrao'])->name('cardapio.item.salvar');
Route::delete('/cardapio/item-padrao/{id}/excluir', [CardapioController::class, 'destroyItemPadrao'])->name('cardapio.item.excluir');
Route::put('/cardapio/{id}/atualizar', [\App\Http\Controllers\CardapioController::class, 'update'])->name('cardapio.atualizar');
Route::post('/cardapio/{id}/excecao', [CardapioController::class, 'storeExcecao'])->name('cardapio.excecao.salvar');
Route::delete('/cardapio/excecao/{id}/excluir', [CardapioController::class, 'destroyExcecao'])->name('cardapio.excecao.excluir');
Route::post('/cardapio/excecao/{id}/item', [CardapioController::class, 'storeItemExcecao'])->name('cardapio.excecao.item.salvar');
Route::delete('/cardapio/excecao/item/{id}/excluir', [CardapioController::class, 'destroyItemExcecao'])->name('cardapio.excecao.item.excluir');

// Outras rotas
Route::get('/', [MainController::class, 'index']);
Route::get('/home', function () {
    return view('dashboard.home');
})->name('home');
Route::post('/teste', [MainController::class, 'teste'])->name('teste');
