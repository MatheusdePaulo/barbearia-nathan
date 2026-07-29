<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        $receitaServicos = Appointment::whereIn('status', ['confirmed', 'finished']) // Adicionado confirmed para testes
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
        $hoje = now()->format('m-d');
        $aniversariantes = User::whereRaw("strftime('%m-%d', birthday) = ?", [$hoje])->get();
        $aniversariantesHoje = $aniversariantes->count();

        return view('admin.birthdays', compact('aniversariantes', 'aniversariantesHoje'));
    }

    public function reports(Request $request)
    {
        $filter = $request->query('filter', 'mes');

        $query = Appointment::where('status', 'finished');
        if($filter == 'hoje') $query->whereDate('date', now());
        if($filter == '7dias') $query->where('date', '>=', now()->subDays(7));
        if($filter == 'mes_passado') $query->whereMonth('date', now()->subMonth()->month);
        if($filter == 'ano') $query->whereYear('date', now()->year);

        $faturamentoServicos = $query->join('services', 'appointments.service_id', '=', 'services.id')->sum('services.price');
        $faturamentoProdutos = 450.00;
        $totalDespesas = 150.00;

        $faturamentoTotal = $faturamentoServicos + $faturamentoProdutos;
        $saldoReal = $faturamentoTotal - $totalDespesas;

        $servicosRealizados = Appointment::where('status', 'finished')->count();
        $ticketMedio = $servicosRealizados > 0 ? ($faturamentoTotal / $servicosRealizados) : 0;

        $totalAgendamentos = Appointment::count();
        $totalFaltas = Appointment::where('status', 'canceled')->count();
        $taxaNoShow = $totalAgendamentos > 0 ? round(($totalFaltas / $totalAgendamentos) * 100) : 0;

        $labelsMensal = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai'];
        $dadosFaturamento = [12000, 15000, 13500, 16000, $faturamentoTotal];

        $servicosPopulares = [
            'labels' => ['Corte', 'Barba', 'Combo', 'Sobrancelha'],
            'data' => [45, 25, 20, 10]
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

    /**
     * MÉTODOS ADICIONADOS PARA RESOLVER O ERRO DE CONFIGURAÇÕES
     */
    public function settings()
    {
        // Dados fictícios para a View não quebrar enquanto não usamos o Eloquent
        $barbearia = [
            'nome' => 'Barber Nathan',
            'whatsapp' => '(81) 98765-4321',
            'email' => 'matheus@barbernathan.com'
        ];

        return view('admin.settings', compact('barbearia'));
    }

    public function updateSettings(Request $request)
    {
        // Lógica de salvamento será implementada na fase do Eloquent
        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
    }
}
