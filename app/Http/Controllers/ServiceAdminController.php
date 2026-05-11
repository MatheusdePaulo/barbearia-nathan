<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User; // Importação explícita para o contador de aniversariantes
use Illuminate\Http\Request;

class ServiceAdminController extends Controller
{
    /**
     * Lista os serviços existentes para o Nathan gerenciar os valores.
     */
    public function index()
    {
        // Busca todos os serviços (Corte, Barba, etc.) do banco de dados
        $services = Service::all();

        // Mantemos a contagem de aniversariantes para o badge no menu lateral do admin
        $hoje = now()->format('m-d');
        $aniversariantesHoje = User::whereRaw("strftime('%m-%d', birthday) = ?", [$hoje])->count();

        // Retorna a view correta na pasta admin/services/index.blade.php
        return view('admin.services.index', compact('services', 'aniversariantesHoje'));
    }

    /**
     * Atualiza apenas o preço e a duração do serviço.
     * Isso impede que o Nathan altere nomes ou descrições e quebre o seu design do Figma.
     */
    public function update(Request $request, $id)
    {
        // Validação rigorosa dos dados financeiros e de tempo
        $request->validate([
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
        ]);

        $service = Service::findOrFail($id);

        // Atualização restrita aos campos permitidos
        $service->update([
            'price' => $request->price,
            'duration' => $request->duration
        ]);

        // Redireciona para a listagem com mensagem de sucesso (Toast/Session)
        return redirect()->route('admin.services.index')
            ->with('success', 'Valores de ' . $service->name . ' atualizados com sucesso!');
    }
}
