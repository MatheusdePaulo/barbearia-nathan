<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Ponto e vírgula corrigido aqui

class AdminController extends Controller
{
    public function index()
    {
        $hoje = now()->format('m-d');
        $aniversariantesHoje = User::whereRaw("strftime('%m-%d', birthday) = ?", [$hoje])->count();

        $stats = [
            'confirmados' => Appointment::where('status', 'confirmed')->count(),
            'pendentes'   => Appointment::where('status', 'pending')->count(),
            'cancelados'  => Appointment::where('status', 'canceled')->count(),
        ];

        $proximasReservas = Appointment::with(['user', 'service'])
            ->whereDate('date', now())
            ->whereIn('status', ['confirmed', 'pending'])
            ->orderBy('time', 'asc')
            ->take(5)
            ->get();

        $visitorsData = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $diaData = now()->subDays($i);
            $labels[] = $diaData->translatedFormat('D');
            $visitorsData[] = Appointment::whereDate('date', $diaData->format('Y-m-d'))->count();
        }

        $receitaServicos = Appointment::whereIn('status', ['confirmed', 'finished'])
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price');

        $receitaProdutos = 0;

        return view('admin.dashboard', compact('stats', 'proximasReservas', 'aniversariantesHoje', 'visitorsData', 'labels', 'receitaServicos', 'receitaProdutos'));
    }

    public function agenda(Request $request)
    {
        $dataSelecionada = $request->query('date', now()->format('Y-m-d'));

        $reservas = Appointment::with(['service', 'user'])
            ->whereDate('date', $dataSelecionada)
            ->orderByRaw("CASE
                WHEN status IN ('pending', 'confirmed') THEN 0
                ELSE 1
            END ASC")
            ->orderBy('time', 'asc')
            ->get();

        $hojeCount = Appointment::whereDate('date', now())->count();
        $mesCount = Appointment::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)->count();

        $faturamentoDia = Appointment::whereDate('date', $dataSelecionada)
            ->whereIn('status', ['confirmed', 'finished'])
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price');

        return view('admin.agenda', compact('reservas', 'hojeCount', 'mesCount', 'faturamentoDia', 'dataSelecionada'));
    }

    public function birthdays()
    {
        $hoje = now();
        $aniversariantes = User::where('is_admin', false)
            ->whereMonth('birthday', $hoje->month)
            ->whereDay('birthday', $hoje->day)
            ->get();

        $aniversariantesHoje = $aniversariantes->count();
        return view('admin.birthdays', compact('aniversariantes', 'aniversariantesHoje'));
    }

    public function reports(Request $request)
    {
        $filter = $request->query('filter', 'mes');
        $queryBase = Appointment::whereIn('status', ['confirmed', 'finished']);

        if($filter == 'hoje') $queryBase->whereDate('date', now());
        if($filter == '7dias') $queryBase->where('date', '>=', now()->subDays(7));
        if($filter == 'mes_passado') $queryBase->whereMonth('date', now()->subMonth()->month);
        if($filter == 'ano') $queryBase->whereYear('date', now()->year);
        if($filter == 'mes') $queryBase->whereMonth('date', now()->month);

        $faturamentoServicos = (clone $queryBase)
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price');

        $faturamentoProdutos = 0.00;
        $totalDespesas = 0.00;
        $faturamentoTotal = $faturamentoServicos + $faturamentoProdutos;
        $saldoReal = $faturamentoTotal - $totalDespesas;

        $servicosRealizados = (clone $queryBase)->count();
        $ticketMedio = $servicosRealizados > 0 ? ($faturamentoTotal / $servicosRealizados) : 0;

        $totalAgendamentos = Appointment::count();
        $totalFaltas = Appointment::where('status', 'canceled')->count();
        $taxaNoShow = $totalAgendamentos > 0 ? round(($totalFaltas / $totalAgendamentos) * 100) : 0;

        $labelsMensal = [];
        $dadosFaturamento = [];
        for ($i = 4; $i >= 0; $i--) {
            $mes = now()->subMonths($i);
            $labelsMensal[] = $mes->translatedFormat('M');
            $dadosFaturamento[] = Appointment::whereIn('status', ['confirmed', 'finished'])
                ->whereMonth('date', $mes->month)
                ->whereYear('date', $mes->year)
                ->join('services', 'appointments.service_id', '=', 'services.id')
                ->sum('services.price');
        }

        $topServicos = Appointment::select('services.name', DB::raw('count(*) as total'))
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->groupBy('services.name')
            ->orderBy('total', 'desc')
            ->take(4)
            ->get();

        $servicosPopulares = [
            'labels' => $topServicos->pluck('name')->toArray() ?: ['Nenhum'],
            'data' => $topServicos->pluck('total')->toArray() ?: [0]
        ];

        $comparativoVendas = [
            'labels' => ['Serviços', 'Produtos'],
            'data' => [$faturamentoServicos, $faturamentoProdutos]
        ];

        return view('admin.reports.index', compact(
            'faturamentoTotal', 'faturamentoProdutos', 'totalDespesas', 'saldoReal',
            'ticketMedio', 'servicosRealizados', 'taxaNoShow', 'labelsMensal',
            'dadosFaturamento', 'servicosPopulares', 'comparativoVendas', 'filter'
        ));
    }

    public function settings()
    {
        $settings = Setting::pluck('value', 'key');

        $barbearia = [
            'nome' => $settings['unit_name'] ?? 'Barber Nathan',
            'whatsapp' => $settings['unit_whatsapp'] ?? '(85) 90000-0000',
            'email' => auth()->user()->email
        ];

        return view('admin.settings', compact('barbearia'));
    }

    public function updateSettings(Request $request)
    {
        if ($request->has('unit_name')) {
            Setting::updateOrCreate(['key' => 'unit_name'], ['value' => $request->unit_name]);
            Setting::updateOrCreate(['key' => 'unit_whatsapp'], ['value' => $request->unit_whatsapp]);

            return redirect()->back()->with('success', 'Configurações da unidade salvas!');
        }

        if ($request->has('password') && $request->filled('password')) {
            $request->validate([
                'password' => 'required|min:8|confirmed',
            ]);

            auth()->user()->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->back()->with('success', 'Senha atualizada com sucesso!');
        }

        return redirect()->back();
    }
}
