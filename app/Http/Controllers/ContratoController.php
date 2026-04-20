<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contrato;

class ContratoController extends Controller
{
    public function listaContratos(Request $request)
    {
        $query = Contrato::with('fornecedor');
        if ($request->filled('fornecedor')) {
            $query->whereHas('fornecedor', function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->fornecedor . '%');
            });
        }
        if ($request->filled('ano') && $request->ano !== 'Todos') {
            $query->where('pregao', 'like', '%/' . $request->ano);
        }
        if ($request->filled('status') && $request->status !== 'Todos') {
            $query->where('status', $request->status);
        }
        $contratos = $query->orderBy('inicio_vigencia', 'desc')->paginate(10);
        $contratos->appends($request->all());

        return view('dashboard.listaContratos', compact('contratos'));
    }

    public function visualizaContrato($id){
        $contrato = Contrato::with(['fornecedor', 'empenhos', 'itens'])->findOrFail($id);
        return view('dashboard.contrato', compact('contrato'));
    }
}
