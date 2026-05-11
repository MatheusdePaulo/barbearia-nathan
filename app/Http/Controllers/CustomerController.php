<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $hoje = Carbon::today();

        $query = User::where('is_admin', false)
            ->leftJoin('appointments', function($join) use ($hoje) {
                $join->on('users.id', '=', 'appointments.user_id')
                    ->where('appointments.date', '>=', $hoje)
                    // Importante: filtrar apenas agendamentos que ainda não foram concluídos/cancelados
                    ->where('appointments.status', 'confirmed');
            })
            ->select(
                'users.*',
                'appointments.date as next_date',
                'appointments.time as next_time',
                'appointments.id as appointment_id' // AQUI ESTÁ O SEGREDO!
            )
            ->orderByRaw('appointments.date IS NULL, appointments.date ASC, appointments.time ASC')
            ->groupBy('users.id');

        // ... restante dos filtros (hoje, amanha, semana) ...
        if ($filter == 'hoje') {
            $query->whereDate('appointments.date', $hoje);
        } elseif ($filter == 'amanha') {
            $query->whereDate('appointments.date', Carbon::tomorrow());
        } elseif ($filter == 'semana') {
            $query->whereBetween('appointments.date', [$hoje, $hoje->copy()->addDays(7)]);
        }

        $customers = $query->get();

        $diaMes = now()->format('m-d');
        $aniversariantesHoje = User::whereRaw("strftime('%m-%d', birthday) = ?", [$diaMes])->count();

        return view('admin.customers.index', compact('customers', 'aniversariantesHoje', 'filter'));
    }
    public function show($id)
    {
        $customer = User::with(['appointments.service'])->findOrFail($id);

        // Contadores para o dashboard do cliente
        $totalCortes = $customer->appointments()->where('status', 'confirmed')->count();
        $naoCompareceu = $customer->appointments()->where('status', 'canceled')->count();

        return view('admin.customers.show', compact('customer', 'totalCortes', 'naoCompareceu'));
    }
}
