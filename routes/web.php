<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ServiceAdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ScheduleOverrideController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CouponController;
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

// Avaliações e cupons (públicos)
Route::get('/avaliar/cliente', [ReviewController::class, 'form'])->name('reviews.form')->middleware('signed');
Route::post('/avaliar', [ReviewController::class, 'store'])->name('reviews.store');
Route::post('/avaliar/cupom', [ReviewController::class, 'generateCoupon'])->name('reviews.generateCoupon');
Route::post('/agendar/validar-cupom', [CouponController::class, 'validateCoupon'])->name('coupons.validate');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
// --- AGENDAMENTOS (Fluxo do Cliente — público, sem login obrigatório) ---
Route::prefix('agendar')->name('appointments.')->group(function () {
    Route::get('/{service?}', [AppointmentController::class, 'create'])->name('create');
    Route::post('/confirmar', [AppointmentController::class, 'store'])->name('store');

    Route::get('/sucesso', function () {
        return view('appointments.success');
    })->name('success');

    Route::get('/status/{id}', function ($id) {
        $appointment = Appointment::find($id);
        return response()->json(['status' => $appointment ? $appointment->status : 'not_found']);
    })->name('status');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // --- PERFIL DO USUÁRIO ---
    Route::get('/dashboard', fn() => redirect('/'))->name('dashboard');

    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });

    // --- PAINEL ADMINISTRATIVO PROTEGIDO (Barber Nathan) ---
    // Segura o Mario Balada aqui: Valida se is_admin == 1 antes de dar acesso
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('admin')
        ->group(function () {

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
        Route::post('/products/{id}/sell', [ProductController::class, 'sell'])->name('products.sell');

        // Agenda e Relatórios (Métricas Financeiras Reais)
        Route::get('/agenda', [AdminController::class, 'agenda'])->name('agenda');
        Route::get('/relatorios', [AdminController::class, 'reports'])->name('reports');
        Route::post('/relatorios/transacao', [AdminController::class, 'storeTransaction'])->name('transactions.store');

        // Configurações e Gestão da Unidade
        Route::get('/configuracoes', [AdminController::class, 'settings'])->name('settings');
        Route::post('/configuracoes/update', [AdminController::class, 'updateSettings'])->name('settings.update');

        // Agendamento Avulso (Modal da Agenda)
        Route::post('/agenda/avulso', [AppointmentController::class, 'storeAvulso'])->name('appointments.avulso');

        // Override de Horário por Dia
        Route::post('/agenda/horario', [ScheduleOverrideController::class, 'upsert'])->name('schedule.upsert');
        Route::post('/agenda/horario/restaurar', [ScheduleOverrideController::class, 'destroy'])->name('schedule.destroy');

        Route::post('/clientes/sorteio', [CustomerController::class, 'draw'])->name('customers.draw');

        // Avaliações (painel admin)
        Route::get('/avaliacoes', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/avaliacoes/preview', function () {
            $user = \App\Models\User::where('is_admin', false)->first();
            return redirect(\URL::temporarySignedRoute('reviews.form', now()->addHours(1), ['user_id' => $user?->id ?? 1]));
        })->name('reviews.preview');

        // Cupons (painel admin)
        Route::resource('cupons', CouponController::class)->names('coupons');
        Route::patch('/cupons/{id}/toggle', [CouponController::class, 'toggle'])->name('coupons.toggle');
    });
});

require __DIR__.'/auth.php';
