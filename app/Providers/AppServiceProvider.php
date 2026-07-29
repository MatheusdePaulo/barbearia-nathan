<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    // Otimização: Garante que o Laravel use o protocolo HTTPS em produção
    // se estiver rodando na Hostinger para evitar redirecionamentos lentos
    if (config('app.env') === 'production') {
        \URL::forceScheme('https');
    }
}
}
