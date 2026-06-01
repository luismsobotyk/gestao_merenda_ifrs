<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\CardapioController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\RetiradaController;
use App\Http\Controllers\GraficoController;

Route::get('/', [MainController::class, 'index']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/loginSubmit', [AuthController::class, 'loginSubmit'])->name('loginSubmit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/home', function () {
        return view('dashboard.home');
    })->name('home');

    // Gestão de Contratos e Fornecedores
    Route::prefix('/contratos')->group(function () {
        Route::get('/', [ContratoController::class, 'listaContratos'])->name('contratos');
        Route::get('/criar', [ContratoController::class, 'criaContrato'])->name('contrato.criar');
        Route::post('/criar/salvar', [ContratoController::class, 'salvaContrato'])->name('contrato.salvar');
    });
    Route::prefix('/contrato')->group(function () {
        Route::get('/{id}', [ContratoController::class, 'visualizaContrato'])->name('contrato.visualizar');
        Route::get('/{id}/editar', [ContratoController::class, 'visualizaContrato'])->name('contrato.editar'); // Ajuste futuro: método edit
        Route::post('/{id}/empenho/salvar', [ContratoController::class, 'salvaEmpenho'])->name('empenho.salvar');
        Route::post('/{id}/pedido/salvar', [ContratoController::class, 'salvaPedido'])->name('pedido.salvar');
    });
    Route::patch('/pedido/{id}/receber', [ContratoController::class, 'receberPedido'])->name('pedido.receber');
    Route::get('/fornecedor/busca-cnpj', [FornecedorController::class, 'buscaPorCnpj'])->name('fornecedor.busca.cnpj');

    // Gestão de Cardápio
    Route::prefix('/cardapio')->group(function () {
        Route::get('/', [CardapioController::class, 'index'])->name('cardapio');
        Route::get('/novo', [CardapioController::class, 'create'])->name('cardapio.novo');
        Route::post('/salvar', [CardapioController::class, 'store'])->name('cardapio.salvar');
        Route::get('/{id}/editar', [CardapioController::class, 'edit'])->name('cardapio.editar');
        Route::post('/{id}/sync', [CardapioController::class, 'syncAll'])->name('cardapio.sync');
        Route::delete('/{id}', [CardapioController::class, 'destroy'])->name('cardapio.excluir');
    });

    // Gestão de Cursos e Alunos
    Route::prefix('/cursos-retirada')->group(function () {
        Route::get('/', [CursoController::class, 'index'])->name('cursos.index');
        Route::post('/sync', [CursoController::class, 'sync'])->name('cursos.sync');
        Route::patch('/{id}/toggle', [CursoController::class, 'toggleMerenda'])->name('cursos.toggle');
        Route::post('/sync-pagina-alunos', [CursoController::class, 'syncAlunosPorPagina'])->name('cursos.sync.alunos');
    });
    Route::get('/alunos', [AlunoController::class, 'index'])->name('alunos.index');

    // Controle de Retirada (Totem e Manual)
    Route::prefix('controle-retirada')->group(function () {
        Route::get('/', [RetiradaController::class, 'index'])->name('retirada.index');
        Route::get('/totem', [RetiradaController::class, 'modoTotem'])->name('retirada.totem');
        Route::get('/manual', [RetiradaController::class, 'modoManual'])->name('retirada.manual');
        Route::patch('/toggle', [RetiradaController::class, 'toggleModo'])->name('retirada.toggle');
        Route::post('/totem/registrar', [RetiradaController::class, 'registrarTotem'])->name('retirada.totem.registrar');
    });

    // Gráficos e Relatórios
    Route::prefix('graficos')->group(function () {
        Route::get('/tipos-merenda', [GraficoController::class, 'tiposMerenda'])->name('graficos.tipos_merenda');
        Route::get('/por-dia-semana', [GraficoController::class, 'porDiaSemana'])->name('graficos.por_dia_semana');
        Route::get('/por-turma', [GraficoController::class, 'porTurma'])->name('graficos.por_turma');
    });

    // Gerencia de usuários
    Route::prefix('usuarios')->group(function () {
        Route::get('/', [\App\Http\Controllers\UserController::class, 'index'])->name('usuarios.index');
        Route::get('/busca-ldap', [\App\Http\Controllers\UserController::class, 'searchLdap'])->name('usuarios.busca_ldap');
        Route::post('/salvar', [\App\Http\Controllers\UserController::class, 'store'])->name('usuarios.salvar');
        Route::delete('/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('usuarios.excluir');
        Route::get('/{id}/historico', [\App\Http\Controllers\UserController::class, 'history'])->name('usuarios.historico');
    });
});
