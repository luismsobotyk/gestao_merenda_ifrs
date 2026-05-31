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
Route::get('/fornecedor/busca-cnpj', [FornecedorController::class, 'buscaPorCnpj'])->name('fornecedor.busca.cnpj');

// Rotas para cardápio
Route::get('/cardapio', [CardapioController::class, 'index'])->name('cardapio');
Route::get('/cardapio/novo', [CardapioController::class, 'create'])->name('cardapio.novo');
Route::post('/cardapio/salvar', [CardapioController::class, 'store'])->name('cardapio.salvar');
Route::get('/cardapio/{id}/editar', [CardapioController::class, 'edit'])->name('cardapio.editar');
Route::post('/cardapio/{id}/sync', [CardapioController::class, 'syncAll'])->name('cardapio.sync');

// Rotas para gestão de curso/discente
Route::get('/cursos-retirada', [CursoController::class, 'index'])->name('cursos.index');
Route::post('/cursos-retirada/sync', [CursoController::class, 'sync'])->name('cursos.sync');
Route::patch('/cursos-retirada/{id}/toggle', [CursoController::class, 'toggleMerenda'])->name('cursos.toggle');
Route::post('/cursos-retirada/sync-pagina-alunos', [CursoController::class, 'syncAlunosPorPagina'])->name('cursos.sync.alunos');
Route::get('/alunos', [AlunoController::class, 'index'])->name('alunos.index');

Route::prefix('controle-retirada')->group(function () {
    Route::get('/', [RetiradaController::class, 'index'])->name('retirada.index');
    Route::get('/totem', [RetiradaController::class, 'modoTotem'])->name('retirada.totem');
    Route::get('/manual', [RetiradaController::class, 'modoManual'])->name('retirada.manual');
    Route::patch('/toggle', [RetiradaController::class, 'toggleModo'])->name('retirada.toggle');
});

// Outras rotas
Route::get('/', [MainController::class, 'index']);
Route::get('/home', function () {
    return view('dashboard.home');
})->name('home');
Route::post('/teste', [MainController::class, 'teste'])->name('teste');
Route::get('/teste-api-ifrs', function (IfrsApiService $api) {
    $response = $api->testarConexao();

    // Se a API retornar um status 200 (OK)
    if ($response->successful()) {
        return response()->json([
            'sucesso' => true,
            'status' => $response->status(),
            'dados' => $response->json() // Traz o corpo da resposta convertido em Array/JSON
        ]);
    }

    // Se der erro de autenticação (401) ou não encontrado (404)
    return response()->json([
        'sucesso' => false,
        'status' => $response->status(),
        'erro' => $response->body() // Mostra a string bruta do erro retornado pelo servidor
    ], $response->status());
});
