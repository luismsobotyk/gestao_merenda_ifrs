<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Services\IfrsApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Aluno;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::orderBy('nivel')->orderBy('nome')->get();

        $ultimaSync = Curso::max('updated_at');
        $ultimaSync = $ultimaSync ? \Carbon\Carbon::parse($ultimaSync)->format('d/m/Y H:i') : 'Nunca';

        return view('dashboard.cursos.index', compact('cursos', 'ultimaSync'));
    }

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
                $curso = Curso::updateOrCreate(
                    ['id_curso' => $item['id_curso']],
                    [
                        'codigo' => $item['codigo'] ?? null,
                        'nome' => $item['curso'],
                        'nivel' => $item['nivel'] ?? null,
                        'turno' => $item['turno'] ?? null,
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

    public function toggleMerenda(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);
        $curso->update([
            'direito_merenda' => $request->direito_merenda
        ]);

        return response()->json(['success' => true, 'message' => 'Status atualizado!']);
    }

    public function syncAlunosPorPagina(Request $request, IfrsApiService $api)
    {
        $cursoIdLocal = $request->curso_id;
        $cursoIdApi = $request->curso_id_api;
        $pagina = $request->pagina;

        $response = $api->buscarAlunosPorCurso($cursoIdApi, $pagina);

        if ($response->failed()) {
            return response()->json(['success' => false, 'error' => 'Falha de comunicação com a API do IFRS'], 500);
        }

        $respostaApi = $response->json();

        $alunosLote = isset($respostaApi['data']) ? $respostaApi['data'] : $respostaApi;

        if (!is_array($alunosLote) || empty($alunosLote)) {
            return response()->json(['success' => true, 'salvos' => 0, 'tem_mais' => false]);
        }

        $salvos = 0;

        DB::beginTransaction();
        try {
            foreach ($alunosLote as $item) {
                if (!is_array($item) || empty($item['matricula'])) continue;

                $nomeAluno = $item['nome_social'] ?: ($item['nome_civil'] ?: ($item['nome_completo'] ?: 'Sem Nome'));

                \App\Models\Aluno::updateOrCreate(
                    ['matricula' => $item['matricula']],
                    [
                        'nome' => $nomeAluno,
                        'login' => $item['login'] ?? null,
                        'curso_id' => $cursoIdLocal
                    ]
                );
                $salvos++;
            }

            DB::commit();

            $temMais = count($alunosLote) >= 25;

            return response()->json(['success' => true, 'salvos' => $salvos, 'tem_mais' => $temMais]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
