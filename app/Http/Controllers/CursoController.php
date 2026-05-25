<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Services\IfrsApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CursoController extends Controller
{
    public function index()
    {
        // Ordena por nível e depois pelo nome do curso
        $cursos = Curso::orderBy('nivel')->orderBy('nome')->get();
        return view('dashboard.cursos.index', compact('cursos'));
    }

    // 2. Bate na API, puxa os dados e atualiza o Banco Local (Upsert)
    public function sync(IfrsApiService $api)
    {
        $response = $api->buscarCursos();

        if ($response->failed()) {
            return redirect()->back()->withErrors('Falha ao comunicar com a API do IFRS. Tente novamente mais tarde.');
        }

        $cursosApi = $response->json();
        $contadorNovos = 0;
        $contadorAtualizados = 0;

        DB::beginTransaction();
        try {
            foreach ($cursosApi as $item) {
                // updateOrCreate procura pelo id_curso. Se achar, atualiza. Se não, cria.
                $curso = Curso::updateOrCreate(
                    ['id_curso' => $item['id_curso']],
                    [
                        'codigo' => $item['codigo'] ?? null,
                        'nome' => $item['curso'],
                        'nivel' => $item['nivel'] ?? null,
                        'turno' => $item['turno'] ?? null,
                        // Não tocamos no 'direito_merenda' aqui, para não apagar a decisão do usuário!
                    ]
                );

                if ($curso->wasRecentlyCreated) {
                    $contadorNovos++;
                } else {
                    $contadorAtualizados++;
                }
            }
            DB::commit();

            return redirect()->back()->with('success', "Sincronização concluída! $contadorNovos cursos novos adicionados e $contadorAtualizados atualizados.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Erro ao processar os dados da API: ' . $e->getMessage());
        }
    }

    // 3. Atualiza o direito à merenda via botão "Toggle" sem recarregar a tela
    public function toggleMerenda(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);
        $curso->update([
            'direito_merenda' => $request->direito_merenda
        ]);

        return response()->json(['success' => true, 'message' => 'Status atualizado!']);
    }
}
