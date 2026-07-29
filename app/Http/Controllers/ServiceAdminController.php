<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceAdminController extends Controller
{
    /**
     * Lista os serviços existentes para o Nathan gerenciar os valores e promoções.
     */
    public function index()
    {
        // Busca todos os serviços
        $services = Service::all();

        // Mantemos a contagem de aniversariantes para o badge no menu lateral
        // Nota: Se você mudou a lógica de aniversariantes para 'amanhã' no AdminController,
        // lembre-se de sincronizar aqui também se necessário.
        $hoje = now()->format('m-d');
        // CORREÇÃO: MySQL usa DATE_FORMAT em vez de strftime
        $aniversariantesHoje = User::whereRaw("DATE_FORMAT(birthday, '%m-%d') = ?", [$hoje])->count();

        return view('admin.services.index', compact('services', 'aniversariantesHoje'));
    }

    /**
     * Atualiza preço, duração e status promocional.
     */
    public function update(Request $request, $id)
    {
        // Validação expandida para os novos campos de promoção
        $request->validate([
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'promo_price' => 'nullable|numeric|min:0|lt:price', // O preço promocional deve ser menor que o original
        ]);

        $service = Service::findOrFail($id);

        // Atualização incluindo a lógica de promoção
        $service->update([
            'price' => $request->price,
            'duration' => $request->duration,
            'is_promo' => $request->has('is_promo'),
            'promo_price' => $request->is_promo ? $request->promo_price : null,
            'is_combo' => $request->has('is_combo'),
        ]);

        return redirect()->route('admin.services.index')
            ->with('success', 'Configurações de ' . $service->name . ' atualizadas com sucesso!');
    }
}
