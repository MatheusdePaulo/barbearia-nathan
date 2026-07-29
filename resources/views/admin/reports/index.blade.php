@extends('layouts.admin')

@section('content')
    <div class="p-4 sm:p-8 space-y-8 bg-[#050505] min-h-screen" x-data="{ openTrans: false, type: 'entrada', category: 'service' }">

        {{-- HEADER COM FILTROS --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="text-center lg:text-left">
                <h1 class="text-xl sm:text-2xl font-black italic text-white uppercase tracking-tighter">Inteligência de Negócio</h1>
                <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-1">Gestão de performance e saúde financeira</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 items-center justify-center">
                <button @click="openTrans = true" class="w-full sm:w-auto bg-[#D4AF37] text-black px-6 py-2.5 rounded-xl text-[10px] font-black uppercase shadow-lg shadow-[#D4AF37]/20 flex items-center justify-center gap-2">
                    <i class="fas fa-plus-circle"></i> Nova Transação
                </button>

                {{-- FILTROS TEMPORAIS --}}
                <div class="flex gap-1 bg-zinc-900/50 p-1 rounded-xl border border-zinc-800 overflow-x-auto no-scrollbar max-w-full">
                    @foreach(['hoje' => 'Hoje', '7dias' => '7 Dias', 'mes' => 'Este Mês', 'mes_passado' => 'Mês Passado', 'ano' => 'Este Ano'] as $key => $label)
                        <a href="?filter={{ $key }}"
                           class="px-3 py-2 rounded-lg text-[9px] font-black uppercase transition-all whitespace-nowrap {{ $filter == $key ? 'bg-zinc-800 text-white border border-zinc-700' : 'text-zinc-500 hover:text-zinc-300' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- GRID KPIs --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="bg-[#121212] p-6 rounded-3xl border border-zinc-800 border-l-4 border-l-green-600 shadow-xl">
                <p class="text-zinc-500 text-[9px] font-black uppercase italic mb-2 tracking-widest">Entradas Totais</p>
                <h3 class="text-2xl font-black italic text-white">R$ {{ number_format($faturamentoTotal, 2, ',', '.') }}</h3>
                <span class="text-green-500 text-[9px] font-bold uppercase mt-2 block tracking-tighter">Serviços + Produtos</span>
            </div>

            <div class="bg-[#121212] p-6 rounded-3xl border border-zinc-800 border-l-4 border-l-red-600 shadow-xl">
                <p class="text-zinc-500 text-[9px] font-black uppercase italic mb-2 tracking-widest">Saídas Totais</p>
                <h3 class="text-2xl font-black italic text-white">R$ {{ number_format($totalDespesas, 2, ',', '.') }}</h3>
                <span class="text-red-500 text-[9px] font-bold uppercase mt-2 block tracking-tighter">Custos Registrados</span>
            </div>

            <div class="bg-[#121212] p-6 rounded-3xl border border-zinc-800 border-l-4 border-l-[#D4AF37] shadow-xl">
                <p class="text-zinc-500 text-[9px] font-black uppercase italic mb-2 tracking-widest">Lucro Líquido</p>
                <h3 class="text-2xl font-black italic text-[#D4AF37]">R$ {{ number_format($saldoReal, 2, ',', '.') }}</h3>
                <span class="text-zinc-600 text-[9px] font-bold uppercase mt-2 block tracking-tighter">Disponível em Caixa</span>
            </div>

            <div class="bg-[#121212] p-6 rounded-3xl border border-zinc-800 shadow-xl">
                <p class="text-zinc-500 text-[9px] font-black uppercase italic mb-2 tracking-widest">Faltas (No-Show)</p>
                <h3 class="text-2xl font-black italic text-red-500">{{ $taxaNoShow }}%</h3>
                <span class="text-zinc-600 text-[9px] font-bold uppercase mt-2 block tracking-tighter">Taxa de Cancelamento</span>
            </div>
        </div>

        {{-- SESSÃO DE GRÁFICOS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            <div class="bg-[#121212] p-6 rounded-[2.5rem] border border-zinc-800 shadow-2xl flex flex-col h-[450px]">
                <h4 class="text-white font-black italic uppercase text-[10px] tracking-widest mb-6 border-b border-zinc-800 pb-4">Evolução de Faturamento</h4>
                <div class="flex-1 min-h-0"><canvas id="revenueChart"></canvas></div>
            </div>

            <div class="bg-[#121212] p-6 rounded-[2.5rem] border border-zinc-800 shadow-2xl flex flex-col h-[450px]">
                <h4 class="text-white font-black italic uppercase text-[10px] tracking-widest mb-6 border-b border-zinc-800 pb-4 text-center">Mix de Atendimentos</h4>
                <div class="flex-1 flex items-center justify-center min-h-0"><canvas id="mixChart"></canvas></div>
                <div class="mt-6 grid grid-cols-2 gap-2 text-[8px] font-black uppercase italic text-zinc-500">
                    @foreach($servicosPopulares['labels'] as $label) <span>• {{ $label }}</span> @endforeach
                </div>
            </div>

            <div class="bg-[#121212] p-6 rounded-[2.5rem] border border-zinc-800 shadow-2xl flex flex-col h-[450px]">
                <h4 class="text-white font-black italic uppercase text-[10px] tracking-widest mb-6 border-b border-zinc-800 pb-4 text-right">Origem da Receita</h4>
                <div class="flex-1 min-h-0"><canvas id="originChart"></canvas></div>
                <p class="text-[9px] text-zinc-600 mt-4 text-center font-bold uppercase italic">Comparativo Serviços vs Venda de Produtos</p>
            </div>
        </div>

        {{-- EXPORTAÇÃO --}}
        <div class="flex flex-col sm:flex-row gap-4 pb-10">
            <button onclick="window.print()" class="flex-1 bg-zinc-900 border border-zinc-800 text-white py-5 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-zinc-800 transition-all flex items-center justify-center gap-3">
                <i class="fas fa-file-pdf text-red-500"></i> Exportar PDF do Relatório
            </button>
            <button onclick="alert('Gerando Planilha...')" class="flex-1 bg-[#D4AF37] text-black py-5 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-[#D4AF37]/10 flex items-center justify-center gap-3 active:scale-95 transition-all">
                <i class="fas fa-file-excel"></i> Gerar Planilha Excel (.xlsx)
            </button>
        </div>

        {{-- MODAL DE TRANSAÇÃO --}}
        <div x-show="openTrans" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm" x-transition.opacity x-cloak>
            <div class="bg-[#121212] border border-zinc-800 p-8 rounded-[2.5rem] w-full max-w-md shadow-2xl relative" @click.away="openTrans = false">

                <h2 class="text-white font-black italic uppercase tracking-tighter text-xl mb-6">Novo Lançamento</h2>

                <form action="{{ route('admin.transactions.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" :value="type">

                    <div class="flex gap-2 mb-4 bg-zinc-900 p-1 rounded-xl">
                        <button type="button" @click="type = 'entrada'" :class="type === 'entrada' ? 'bg-green-600 text-white shadow-lg' : 'text-zinc-500'" class="flex-1 py-2 rounded-lg text-[9px] font-black uppercase transition-all">Entrada</button>
                        <button type="button" @click="type = 'saida'" :class="type === 'saida' ? 'bg-red-600 text-white shadow-lg' : 'text-zinc-500'" class="flex-1 py-2 rounded-lg text-[9px] font-black uppercase transition-all">Saída</button>
                    </div>

                    {{-- SELETOR DE CATEGORIA (Apenas para Entrada) --}}
                    <template x-if="type === 'entrada'">
                        <div class="space-y-1">
                            <label class="text-[8px] font-bold text-zinc-500 uppercase ml-2 tracking-widest">Categoria da Receita</label>
                            <select name="category" class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-white text-[10px] font-bold uppercase focus:border-[#D4AF37] outline-none appearance-none cursor-pointer">
                                <option value="service">Corte de Cabelo / Barba</option>
                                <option value="product">Venda de Produto</option>
                            </select>
                        </div>
                    </template>

                    <div class="space-y-1">
                        <label class="text-[8px] font-bold text-zinc-500 uppercase ml-2 tracking-widest">Descrição</label>
                        <input type="text" name="description" required placeholder="Ex: Conta de Luz, Venda Manual..." class="w-full bg-zinc-900 border-zinc-800 rounded-2xl p-4 text-white text-xs focus:border-[#D4AF37] outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[8px] font-bold text-zinc-500 uppercase ml-2 tracking-widest">Valor (R$)</label>
                        <input type="number" name="amount" step="0.01" required placeholder="0,00" class="w-full bg-zinc-900 border-zinc-800 rounded-2xl p-4 text-white text-xs focus:border-[#D4AF37] outline-none font-mono">
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="openTrans = false" class="flex-1 bg-zinc-800 text-white py-4 rounded-2xl font-black text-[10px] uppercase">Sair</button>
                        <button type="submit" :class="type === 'entrada' ? 'bg-green-600' : 'bg-red-600'" class="flex-[2] text-white py-4 rounded-2xl font-black text-[10px] uppercase shadow-lg">
                            Confirmar <span x-text="type"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        Chart.defaults.color = '#52525b';
        Chart.defaults.font.family = 'Urbanist, sans-serif';

        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: @json($labelsMensal),
                datasets: [{ data: @json($dadosFaturamento), borderColor: '#D4AF37', backgroundColor: 'rgba(212, 175, 55, 0.05)', borderWidth: 4, fill: true, tension: 0.4, pointRadius: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { grid: { color: '#1a1a1a' } }, x: { grid: { display: false } } } }
        });

        new Chart(document.getElementById('mixChart'), {
            type: 'doughnut',
            data: {
                labels: @json($servicosPopulares['labels']),
                datasets: [{ data: @json($servicosPopulares['data']), backgroundColor: ['#D4AF37', '#1a1a1a', '#27272a', '#3f3f46'], borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '80%', plugins: { legend: { display: false } } }
        });

        new Chart(document.getElementById('originChart'), {
            type: 'bar',
            data: {
                labels: @json($comparativoVendas['labels']),
                datasets: [{ data: @json($comparativoVendas['data']), backgroundColor: ['#D4AF37', '#27272a'], borderRadius: 10 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { grid: { color: '#1a1a1a' } }, x: { grid: { display: false } } } }
        });
    </script>
@endsection
