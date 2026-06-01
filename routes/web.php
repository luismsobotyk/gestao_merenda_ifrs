<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\CardapioController;
use App\Services\IfrsApiService;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\RetiradaController;
use App\Http\Controllers\GraficoController;
Route::get('/', [MainController::class, 'index']);

// Rotas de autenticação
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/loginSubmit', [AuthController::class, 'loginSubmit'])->name('loginSubmit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', function () {
    return view('dashboard.home');
})->name('home');

// Rotas para gestão de contratos
Route::prefix('/contratos')->group(callback: function () {
    Route::get('/', [ContratoController::class, 'listaContratos'])->name('contratos');
    Route::get('/criar', [ContratoController::class, 'criaContrato'])->name('contrato.criar');
    Route::post('/criar/salvar', [ContratoController::class, 'salvaContrato'])->name('contrato.salvar');
});
Route::prefix('/contrato')->group(callback: function () {
    Route::get('/{id}', [ContratoController::class, 'visualizaContrato'])->name('contrato.visualizar');
    Route::get('/{id}/editar', [ContratoController::class, 'visualizaContrato'])->name('contrato.editar');
    Route::post('/{id}/empenho/salvar', [ContratoController::class, 'salvaEmpenho'])->name('empenho.salvar');
    Route::post('/{id}/pedido/salvar', [ContratoController::class, 'salvaPedido'])->name('pedido.salvar');
});
Route::patch('/pedido/{id}/receber', [ContratoController::class, 'receberPedido'])->name('pedido.receber');
Route::get('/fornecedor/busca-cnpj', [FornecedorController::class, 'buscaPorCnpj'])->name('fornecedor.busca.cnpj');

// Rotas para cardápio
Route::prefix('/cardapio')->group(callback: function () {
    Route::get('/', [CardapioController::class, 'index'])->name('cardapio');
    Route::get('/novo', [CardapioController::class, 'create'])->name('cardapio.novo');
    Route::post('/salvar', [CardapioController::class, 'store'])->name('cardapio.salvar');
    Route::get('/{id}/editar', [CardapioController::class, 'edit'])->name('cardapio.editar');
    Route::post('/{id}/sync', [CardapioController::class, 'syncAll'])->name('cardapio.sync');
});

// Rotas para gestão de curso/discente
Route::prefix('/cursos-retirada')->group(callback: function () {
    Route::get('/', [CursoController::class, 'index'])->name('cursos.index');
    Route::post('/sync', [CursoController::class, 'sync'])->name('cursos.sync');
    Route::patch('/{id}/toggle', [CursoController::class, 'toggleMerenda'])->name('cursos.toggle');
    Route::post('/sync-pagina-alunos', [CursoController::class, 'syncAlunosPorPagina'])->name('cursos.sync.alunos');
});
Route::get('/alunos', [AlunoController::class, 'index'])->name('alunos.index');

Route::prefix('controle-retirada')->group(function () {
    Route::get('/', [RetiradaController::class, 'index'])->name('retirada.index');
    Route::get('/totem', [RetiradaController::class, 'modoTotem'])->name('retirada.totem');
    Route::get('/manual', [RetiradaController::class, 'modoManual'])->name('retirada.manual');
    Route::patch('/toggle', [RetiradaController::class, 'toggleModo'])->name('retirada.toggle');
    Route::post('/totem/registrar', [RetiradaController::class, 'registrarTotem'])->name('retirada.totem.registrar');
});

Route::prefix('graficos')->group(function () {
    Route::get('/tipos-merenda', [GraficoController::class, 'tiposMerenda'])->name('graficos.tipos_merenda');
    Route::get('/por-dia-semana', [App\Http\Controllers\GraficoController::class, 'porDiaSemana'])->name('graficos.por_dia_semana');
    Route::get('/por-turma', [App\Http\Controllers\GraficoController::class, 'porTurma'])->name('graficos.por_turma');
});
