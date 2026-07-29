<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;
<<<<<<< HEAD
use Illuminate\Support\Facades\DB;
=======
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $search = $request->query('search');
        $hoje = Carbon::today();

<<<<<<< HEAD
        // Criamos uma subquery para pegar apenas o ID do próximo agendamento confirmado de cada usuário
        // Isso evita o erro de ONLY_FULL_GROUP_BY no MySQL da Hostinger
        $subquery = Appointment::select('user_id', DB::raw('MIN(id) as next_appointment_id'))
            ->where('date', '>=', $hoje)
            ->where('status', 'confirmed')
            ->groupBy('user_id');

        $query = User::where('is_admin', false)
            ->leftJoinSub($subquery, 'next_appt', function ($join) {
                $join->on('users.id', '=', 'next_appt.user_id');
            })
            ->leftJoin('appointments', 'next_appt.next_appointment_id', '=', 'appointments.id')
=======
        $query = User::where('is_admin', false)
            ->leftJoin('appointments', function($join) use ($hoje) {
                $join->on('users.id', '=', 'appointments.user_id')
                    ->where('appointments.date', '>=', $hoje)
                    ->where('appointments.status', 'confirmed');
            })
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
            ->select(
                'users.*',
                'appointments.date as next_date',
                'appointments.time as next_time',
                'appointments.id as appointment_id'
            )
<<<<<<< HEAD
            // No MySQL, ordenamos nulos por último de forma explícita
            ->orderByRaw('CASE WHEN appointments.date IS NULL THEN 1 ELSE 0 END ASC')
            ->orderBy('appointments.date', 'asc')
            ->orderBy('appointments.time', 'asc');
=======
            ->orderByRaw('appointments.date IS NULL, appointments.date ASC, appointments.time ASC')
            ->groupBy('users.id');
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b

        if ($search) {
            $query->where('users.name', 'like', "%{$search}%");
        }

        if ($filter == 'hoje') {
            $query->whereDate('appointments.date', $hoje);
        } elseif ($filter == 'amanha') {
            $query->whereDate('appointments.date', Carbon::tomorrow());
        } elseif ($filter == 'semana') {
            $query->whereBetween('appointments.date', [$hoje, $hoje->copy()->addDays(7)]);
        }

<<<<<<< HEAD
        $customers = $query->paginate(20)->withQueryString();

        $diaMes = now()->format('m-d');
        // CORREÇÃO MANTIDA: MySQL usa DATE_FORMAT
        $aniversariantesHoje = User::whereRaw("DATE_FORMAT(birthday, '%m-%d') = ?", [$diaMes])->count();
=======
        $customers = $query->get();

        $diaMes = now()->format('m-d');
        $aniversariantesHoje = User::whereRaw("strftime('%m-%d', birthday) = ?", [$diaMes])->count();
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b

        return view('admin.customers.index', compact('customers', 'aniversariantesHoje', 'filter'));
    }

<<<<<<< HEAD
=======
    /**
     * Lógica do Sorteio Barber Nathan
     */
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
    public function draw(Request $request)
    {
        $request->validate([
            'type' => 'required|in:all,recent',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $query = User::where('is_admin', false);

        if ($request->type == 'recent') {
            $query->where('created_at', '>=', now()->subMonth());
        }

<<<<<<< HEAD
=======
        // ... lógica anterior do sorteio
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
        $winners = $query->inRandomOrder()->limit($request->quantity)->get();

        if ($winners->isEmpty()) {
            return redirect()->back()->with('error', 'Nenhum cliente elegível.');
        }

<<<<<<< HEAD
=======
        // O toArray() garante que a estrutura seja previsível para a sessão
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
        return redirect()->back()->with('winners', $winners->toArray());
    }

    public function show($id)
    {
        $customer = User::with(['appointments.service'])->findOrFail($id);
        $totalCortes = $customer->appointments()->where('status', 'confirmed')->count();
        $naoCompareceu = $customer->appointments()->where('status', 'canceled')->count();

        return view('admin.customers.show', compact('customer', 'totalCortes', 'naoCompareceu'));
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
