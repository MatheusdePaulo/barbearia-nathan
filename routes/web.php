<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ServiceAdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\WebhookController; // Adicionado para automação do Pix
use App\Models\Appointment;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [ServiceController::class, 'index'])->name('home');

// Rota de Webhook (Fora de qualquer Middleware de Autenticação)
Route::post('/webhooks/mercadopago', [WebhookController::class, 'handleMercadoPago'])
    ->name('webhooks.mercadopago');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // --- AGENDAMENTOS (Fluxo do Cliente) ---
    Route::prefix('agendar')->name('appointments.')->group(function () {
        Route::get('/{service?}', [AppointmentController::class, 'create'])->name('create');
        Route::post('/confirmar', [AppointmentController::class, 'store'])->name('store');

        // ADICIONADA: Rota de Sucesso para o Mercado Pago
        Route::get('/sucesso', function () {
            return view('appointments.success');
        })->name('success');

        Route::get('/status/{id}', function ($id) {
            $appointment = Appointment::find($id);
            return response()->json(['status' => $appointment ? $appointment->status : 'not_found']);
        })->name('status');
    });

    // --- PERFIL DO USUÁRIO ---
    Route::get('/dashboard', fn() => redirect('/'))->name('dashboard');

    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });

    // --- PAINEL ADMINISTRATIVO (Barber Nathan) ---
    Route::prefix('admin')->name('admin.')->group(function () {

        // Dashboard Principal e Marketing
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/aniversariantes', [AdminController::class, 'birthdays'])->name('birthdays');

        // Gestão de Clientes e Histórico
        Route::controller(CustomerController::class)->group(function () {
            Route::get('/clientes', 'index')->name('customers');
            Route::get('/clientes/{id}', 'show')->name('customers.show');
        });

        // Gestão de Agendamentos (Status: Concluído / Faltou)
        Route::patch('/agendamentos/{id}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');

        // Gestão de Serviços (Preço e Duração)
        Route::get('/servicos', [ServiceAdminController::class, 'index'])->name('services.index');
        Route::put('/servicos/{id}', [ServiceAdminController::class, 'update'])->name('services.update');

        // Gestão de Produtos (CRUD Completo)
        Route::resource('products', ProductController::class);

        // Agenda e Relatórios (Métricas Financeiras Reais)
        Route::get('/agenda', [AdminController::class, 'agenda'])->name('agenda');
        Route::get('/relatorios', [AdminController::class, 'reports'])->name('reports');

        // Configurações e Gestão da Unidade
        Route::get('/configuracoes', [AdminController::class, 'settings'])->name('settings');
        Route::post('/configuracoes/update', [AdminController::class, 'updateSettings'])->name('settings.update');

        // Agendamento Avulso (Modal da Agenda)
        Route::post('/agenda/avulso', [AppointmentController::class, 'storeAvulso'])->name('appointments.avulso');

        Route::post('/clientes/sorteio', [CustomerController::class, 'draw'])->name('customers.draw');


    });
});

require __DIR__.'/auth.php';
