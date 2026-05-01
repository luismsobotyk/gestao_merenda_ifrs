<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function buscaPorCnpj(Request $request){
        $fornecedor = Fornecedor::where('cnpj', $request->query('cnpj'))->first();
        if ($fornecedor) {
            return response()->json($fornecedor);
        }
        return response()->json(['message' => 'Fornecedor não encontrado'], 404);
    }
}
