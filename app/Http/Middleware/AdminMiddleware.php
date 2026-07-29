<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Garante que apenas administradores acessem a rota.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se o usuário não estiver logado OU is_admin for falso/zero no banco
        if (!auth()->check() || !auth()->user()->is_admin) {
            // Chuta de volta para a Home com mensagem de erro
            return redirect('/')->with('error', 'Acesso negado! Área exclusiva do administrador.');
        }

        return $next($request);
    }
}
