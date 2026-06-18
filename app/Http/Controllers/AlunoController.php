<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Curso;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    public function index(Request $request)
    {
        $query = Aluno::with('curso');

        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(function($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                    ->orWhere('matricula', 'like', "%{$busca}%");
            });
        }

        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }

        $alunos = $query->orderBy('nome')->paginate(30)->withQueryString();
        $cursos = Curso::where('direito_merenda', true)->orderBy('nome')->get();
        $cursosAutorizados = $cursos->values();

        $ultimaSync = Aluno::max('created_at');
        $ultimaSync = $ultimaSync ? \Carbon\Carbon::parse($ultimaSync)->format('d/m/Y H:i') : 'Nunca';

        return view('dashboard.alunos.index', compact('alunos', 'cursos', 'cursosAutorizados', 'ultimaSync'));
    }
}
