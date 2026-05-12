<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Transaction;

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

        // Base das Queries
        $queryBase = Appointment::whereIn('status', ['confirmed', 'finished']);
        $queryTrans = Transaction::query();

        // Filtros Temporais (Centralizados para ambas as tabelas)
        if ($filter == 'hoje') {
            $queryBase->whereDate('date', now());
            $queryTrans->whereDate('date', now());
        } elseif ($filter == '7dias') {
            $queryBase->where('date', '>=', now()->subDays(7));
            $queryTrans->where('date', '>=', now()->subDays(7));
        } elseif ($filter == 'mes_passado') {
            $queryBase->whereMonth('date', now()->subMonth()->month)
                ->whereYear('date', now()->subMonth()->year);
            $queryTrans->whereMonth('date', now()->subMonth()->month)
                ->whereYear('date', now()->subMonth()->year);
        } elseif ($filter == 'ano') {
            $queryBase->whereYear('date', now()->year);
            $queryTrans->whereYear('date', now()->year);
        } else {
            $queryBase->whereMonth('date', now()->month)
                ->whereYear('date', now()->year);
            $queryTrans->whereMonth('date', now()->month)
                ->whereYear('date', now()->year);
        }

        // 1. CÁLCULO DE ENTRADAS POR CATEGORIA

        // Entradas de Serviços (Agendamentos + Lançamentos Manuais 'service')
        $faturamentoServicos = (clone $queryBase)
                ->join('services', 'appointments.service_id', '=', 'services.id')
                ->sum('services.price')
            + (clone $queryTrans)->where('type', 'income')->where('category', 'service')->sum('amount');

        // Faturamento de Produtos (Tudo que for marcado como 'product' no modal)
        $faturamentoProdutos = (clone $queryTrans)
            ->where('type', 'income')
            ->where('category', 'product')
            ->sum('amount');

        $faturamentoTotal = $faturamentoServicos + $faturamentoProdutos;

        // 2. CÁLCULO DE SAÍDAS
        $totalDespesas = (clone $queryTrans)->where('type', 'expense')->sum('amount');

        // 3. SALDO REAL
        $saldoReal = $faturamentoTotal - $totalDespesas;

        // Métricas de Performance
        $servicosRealizados = (clone $queryBase)->count();
        $ticketMedio = $servicosRealizados > 0 ? ($faturamentoTotal / $servicosRealizados) : 0;

        $totalAgendamentos = Appointment::count();
        $totalFaltas = Appointment::where('status', 'canceled')->count();
        $taxaNoShow = $totalAgendamentos > 0 ? round(($totalFaltas / $totalAgendamentos) * 100) : 0;

        // GRÁFICO 1: Evolução Mensal (Melhoria: Incluindo produtos e serviços manuais no histórico)
        $labelsMensal = [];
        $dadosFaturamento = [];
        for ($i = 4; $i >= 0; $i--) {
            $mes = now()->subMonths($i);
            $labelsMensal[] = $mes->translatedFormat('M');

            $faturadoMesAgendamentos = Appointment::whereIn('status', ['confirmed', 'finished'])
                ->whereMonth('date', $mes->month)->whereYear('date', $mes->year)
                ->join('services', 'appointments.service_id', '=', 'services.id')
                ->sum('services.price');

            $faturadoMesTransacoes = Transaction::where('type', 'income')
                ->whereMonth('date', $mes->month)->whereYear('date', $mes->year)
                ->sum('amount');

            $dadosFaturamento[] = $faturadoMesAgendamentos + $faturadoMesTransacoes;
        }

        // GRÁFICO 2: Mix de Atendimentos
        $topServicos = Appointment::select('services.name', DB::raw('count(*) as total'))
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->groupBy('services.name')
            ->orderBy('total', 'desc')->take(4)->get();

        $servicosPopulares = [
            'labels' => $topServicos->pluck('name')->toArray() ?: ['Nenhum'],
            'data' => $topServicos->pluck('total')->toArray() ?: [0]
        ];

        // GRÁFICO 3: Comparativo (Serviços vs Produtos)
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
     * Método para salvar a transação vinda do modal com suporte a categoria
     */
    public function storeTransaction(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:entrada,saida',
            'category' => 'nullable|in:service,product' // Recebe a categoria do Modal
        ]);

        Transaction::create([
            'description' => $request->description,
            'amount' => $request->amount,
            'type' => $request->type == 'entrada' ? 'income' : 'expense',
            // Se for entrada, grava a categoria vinda do Modal (service ou product)
            'category' => $request->type == 'entrada' ? ($request->category ?? 'service') : 'expense',
            'date' => now(),
        ]);

        return redirect()->back()->with('success', 'Lançamento registrado com sucesso!');
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
