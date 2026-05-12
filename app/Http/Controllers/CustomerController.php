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
        $search = $request->query('search');
        $hoje = Carbon::today();

        $query = User::where('is_admin', false)
            ->leftJoin('appointments', function($join) use ($hoje) {
                $join->on('users.id', '=', 'appointments.user_id')
                    ->where('appointments.date', '>=', $hoje)
                    ->where('appointments.status', 'confirmed');
            })
            ->select(
                'users.*',
                'appointments.date as next_date',
                'appointments.time as next_time',
                'appointments.id as appointment_id'
            )
            ->orderByRaw('appointments.date IS NULL, appointments.date ASC, appointments.time ASC')
            ->groupBy('users.id');

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

        $customers = $query->get();

        $diaMes = now()->format('m-d');
        $aniversariantesHoje = User::whereRaw("strftime('%m-%d', birthday) = ?", [$diaMes])->count();

        return view('admin.customers.index', compact('customers', 'aniversariantesHoje', 'filter'));
    }

    /**
     * Lógica do Sorteio Barber Nathan
     */
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

        // ... lógica anterior do sorteio
        $winners = $query->inRandomOrder()->limit($request->quantity)->get();

        if ($winners->isEmpty()) {
            return redirect()->back()->with('error', 'Nenhum cliente elegível.');
        }

        // O toArray() garante que a estrutura seja previsível para a sessão
        return redirect()->back()->with('winners', $winners->toArray());
    }

    public function show($id)
    {
        $customer = User::with(['appointments.service'])->findOrFail($id);
        $totalCortes = $customer->appointments()->where('status', 'confirmed')->count();
        $naoCompareceu = $customer->appointments()->where('status', 'canceled')->count();

        return view('admin.customers.show', compact('customer', 'totalCortes', 'naoCompareceu'));
    }
}
