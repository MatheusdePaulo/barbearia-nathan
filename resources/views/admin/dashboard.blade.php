@extends('layouts.admin')

@section('content')
    <header class="h-auto lg:h-20 border-b border-zinc-800 flex flex-col lg:flex-row items-center justify-between px-6 lg:px-10 py-4 lg:py-0 bg-[#0A0A0A]/50 backdrop-blur-xl shrink-0 gap-4">
        <div class="flex items-center gap-4 bg-zinc-900 px-4 py-2 rounded-xl border border-zinc-800 w-full lg:w-auto">
            <i class="fas fa-search text-zinc-500 text-sm"></i>
            <input type="text" placeholder="Buscar cliente..." class="bg-transparent border-none outline-none text-sm text-white w-full lg:w-64 placeholder-zinc-600">
        </div>

        <div class="flex items-center gap-4 border-l-0 lg:border-l border-zinc-800 pl-0 lg:pl-6 w-full lg:w-auto justify-end">
            <div class="text-right">
<<<<<<< HEAD
                {{-- Busca o nome dinamicamente do banco de dados --}}
                <p class="text-xs font-black uppercase tracking-widest text-white leading-none mb-1">{{ auth()->user()->name }}</p>
                <p class="text-[9px] text-[#D4AF37] font-bold uppercase tracking-widest leading-none">Administrador</p>
            </div>
            {{-- Atualiza também o avatar para usar o nome dinâmico --}}
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=D4AF37&color=000&bold=true" class="w-10 h-10 rounded-xl border border-zinc-800 shadow-xl">
=======
                <p class="text-xs font-black uppercase tracking-widest text-white leading-none mb-1">Matheus de Paulo</p>
                <p class="text-[9px] text-[#D4AF37] font-bold uppercase tracking-widest leading-none">Administrador</p>
            </div>
            <img src="https://ui-avatars.com/api/?name=Matheus+de+Paulo&background=D4AF37&color=000&bold=true" class="w-10 h-10 rounded-xl border border-zinc-800 shadow-xl">
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
        </div>
    </header>

    <div class="flex-1 overflow-hidden flex flex-col lg:flex-row">

        <div class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scroll flex flex-col space-y-8 lg:order-first">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 order-1">

                <div class="bg-[#121212] p-6 rounded-3xl border border-zinc-800 shadow-xl border-l-4 border-l-green-600 group hover:border-green-500/30 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-green-500/10 rounded-xl flex items-center justify-center text-green-500 shadow-[0_0_15px_rgba(34,197,94,0.1)]">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span class="text-[10px] font-black text-green-500 uppercase">+10%</span>
                    </div>
                    <h3 class="text-2xl font-black italic text-white">{{ str_pad($stats['confirmados'], 2, '0', STR_PAD_LEFT) }}</h3>
                    <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-1">Confirmados</p>
                </div>

                <div class="bg-[#121212] p-6 rounded-3xl border border-zinc-800 shadow-xl border-l-4 border-l-[#D4AF37] group hover:border-[#D4AF37]/30 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-[#D4AF37]/10 rounded-xl flex items-center justify-center text-[#D4AF37] shadow-[0_0_15px_rgba(212,175,55,0.1)]">
                            <i class="fas fa-clock"></i>
                        </div>
                        <span class="text-[10px] font-black text-[#D4AF37] uppercase">Aguardando</span>
                    </div>
                    <h3 class="text-2xl font-black italic text-[#D4AF37]">{{ str_pad($stats['pendentes'], 2, '0', STR_PAD_LEFT) }}</h3>
                    <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-1">Pendentes</p>
                </div>

                <div class="bg-[#121212] p-6 rounded-3xl border border-zinc-800 shadow-xl border-l-4 border-l-red-600 sm:col-span-2 lg:col-span-1 group hover:border-red-500/30 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-red-500/10 rounded-xl flex items-center justify-center text-red-500 shadow-[0_0_15px_rgba(239,68,68,0.1)]">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <span class="text-[10px] font-black text-red-500 uppercase">-5%</span>
                    </div>
                    <h3 class="text-2xl font-black italic text-white">{{ str_pad($stats['cancelados'], 2, '0', STR_PAD_LEFT) }}</h3>
                    <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-1">Cancelados</p>
                </div>
            </div>

<<<<<<< HEAD
=======
            <div class="block lg:hidden order-2">
                <div class="bg-[#121212] p-6 rounded-3xl border border-zinc-800 shadow-xl">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="font-black italic uppercase text-[11px] text-white tracking-widest">
                            {{ now()->translatedFormat('F, Y') }}
                        </h4>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; text-align: center;">
                        @php
                            $hoje = now()->day;
                            $ultimoDia = now()->daysInMonth;
                            $primeiroDiaSemana = now()->startOfMonth()->dayOfWeek;
                        @endphp
                        @for ($i = 0; $i < $primeiroDiaSemana; $i++) <span></span> @endfor
                        @for ($i = 1; $i <= $ultimoDia; $i++)
                            <span class="flex items-center justify-center text-[10px] w-7 h-7 rounded-lg {{ $i == $hoje ? 'bg-[#D4AF37] text-black font-black shadow-[0_0_15px_rgba(212,175,55,0.5)]' : 'text-zinc-500' }}">
                                {{ $i }}
                            </span>
                        @endfor
                    </div>
                </div>
            </div>

>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
            <div class="bg-[#121212] border border-zinc-800 rounded-3xl overflow-hidden shadow-2xl order-3">
                <div class="p-6 border-b border-zinc-800 flex justify-between items-center bg-zinc-900/30">
                    <h3 class="font-black italic uppercase tracking-widest text-xs text-white">Próximas Reservas</h3>
                    <a href="{{ route('admin.agenda') }}" class="text-[10px] font-black text-[#D4AF37] uppercase hover:underline">Ver Todos</a>
                </div>
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle p-6">
                        <table class="w-full text-left">
                            <thead class="text-zinc-500 uppercase tracking-widest text-[10px] border-b border-zinc-800">
                            <tr>
                                <th class="pb-4 font-bold">Cliente</th>
                                <th class="pb-4 font-bold">Serviço</th>
                                <th class="pb-4 font-bold">Horário</th>
                                <th class="pb-4 font-bold text-right">Status</th>
                            </tr>
                            </thead>
                            <tbody class="text-zinc-300 text-xs">
                            @forelse($proximasReservas as $reserva)
                                <tr class="border-b border-zinc-800/50">
<<<<<<< HEAD
=======
                                    {{-- AJUSTE BACK-END: Prioriza o relacionamento 'user' (cliente cadastrado) ou usa o 'client_name' (avulso) --}}
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                                    <td class="py-4 font-bold italic whitespace-nowrap">
                                        {{ $reserva->user->name ?? ($reserva->client_name ?? 'Cliente Avulso') }}
                                    </td>
                                    <td class="py-4 text-zinc-400 uppercase text-[10px] font-bold tracking-tighter whitespace-nowrap">{{ $reserva->service->name ?? 'Serviço' }}</td>
                                    <td class="py-4 font-mono text-[11px]">{{ $reserva->time }}</td>
                                    <td class="py-4 text-right whitespace-nowrap">
                                        <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase {{ $reserva->status == 'confirmed' ? 'bg-green-500/10 text-green-500' : 'bg-[#D4AF37]/10 text-[#D4AF37]' }}">
                                            {{ $reserva->status == 'confirmed' ? 'Confirmado' : 'Pendente' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-8 text-center text-zinc-500 uppercase text-[10px] font-black tracking-widest">Nenhuma reserva para hoje</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 pb-8 order-4">
                <div class="bg-[#121212] p-6 lg:p-8 rounded-3xl border border-zinc-800 shadow-xl">
                    <div class="flex justify-between items-center mb-8">
                        <h4 class="text-white font-black italic uppercase text-xs tracking-widest">Total de Visitantes</h4>
                    </div>
                    <div class="h-48 sm:h-64">
                        <canvas id="visitorsChart"></canvas>
                    </div>
                </div>

                <div class="bg-[#121212] p-6 lg:p-8 rounded-3xl border border-zinc-800 shadow-xl">
                    <div class="flex justify-between items-center mb-8">
                        <h4 class="text-white font-black italic uppercase text-xs tracking-widest">Receita</h4>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center gap-8">
                        <div class="flex-1 w-full text-center sm:text-left">
                            <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-tighter">Receita Total</p>
                            <h3 class="text-3xl font-black italic text-white mb-2">R$ {{ number_format($receitaServicos + $receitaProdutos, 2, ',', '.') }}</h3>
                            <div class="mt-6 space-y-3 inline-block sm:block text-left">
                                <div class="flex items-center gap-3 text-[10px] font-bold uppercase text-zinc-400 tracking-tighter">
<<<<<<< HEAD
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#D4AF37]"></span> Serviços (R$ {{ number_format($receitaServicos, 2, ',', '.') }})
                                </div>
                                <div class="flex items-center gap-3 text-[10px] font-bold uppercase text-zinc-400 tracking-tighter">
                                    <span class="w-2.5 h-2.5 rounded-full bg-zinc-700"></span> Produtos (R$ {{ number_format($receitaProdutos, 2, ',', '.') }})
=======
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#D4AF37]"></span> Serviços
                                </div>
                                <div class="flex items-center gap-3 text-[10px] font-bold uppercase text-zinc-400 tracking-tighter">
                                    <span class="w-2.5 h-2.5 rounded-full bg-zinc-800"></span> Produtos
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                                </div>
                            </div>
                        </div>
                        <div class="w-32 h-32 sm:w-40 sm:h-40 relative flex-shrink-0">
                            <canvas id="revenueChart"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
<<<<<<< HEAD
                                <span class="text-white font-black text-lg lg:text-xl italic leading-none">
                                    @php
                                        $total = $receitaServicos + $receitaProdutos;
                                        $percent = $total > 0 ? round(($receitaServicos / $total) * 100) : 0;
                                    @endphp
                                    {{ $percent }}%
                                </span>
=======
                                <span class="text-white font-black text-lg lg:text-xl italic leading-none">{{ $receitaServicos > 0 ? '100%' : '0%' }}</span>
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden lg:flex w-80 bg-[#0A0A0A] border-l border-zinc-800 p-6 flex-col gap-8">
            <div class="bg-[#121212] p-6 rounded-3xl border border-zinc-800 shadow-xl">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="font-black italic uppercase text-[11px] text-white tracking-widest">
                        {{ now()->translatedFormat('F, Y') }}
                    </h4>
                </div>
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; text-align: center;">
                    @php
<<<<<<< HEAD
                        $hojeDia = now()->day;
=======
                        $hoje = now()->day;
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                        $ultimoDia = now()->daysInMonth;
                        $primeiroDiaSemana = now()->startOfMonth()->dayOfWeek;
                    @endphp
                    @for ($i = 0; $i < $primeiroDiaSemana; $i++) <span></span> @endfor
                    @for ($i = 1; $i <= $ultimoDia; $i++)
<<<<<<< HEAD
                        <span class="flex items-center justify-center text-[10px] w-7 h-7 rounded-lg {{ $i == $hojeDia ? 'bg-[#D4AF37] text-black font-black shadow-[0_0_15_rgba(212,175,55,0.5)]' : 'text-zinc-500' }}">
=======
                        <span class="flex items-center justify-center text-[10px] w-7 h-7 rounded-lg {{ $i == $hoje ? 'bg-[#D4AF37] text-black font-black shadow-[0_0_15_rgba(212,175,55,0.5)]' : 'text-zinc-500' }}">
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                            {{ $i }}
                        </span>
                    @endfor
                </div>
            </div>

            <div class="space-y-6">
                <h4 class="font-black italic uppercase text-[10px] text-zinc-500 tracking-[0.2em] ml-2">Barbeiros</h4>
                <div class="flex items-center justify-between bg-[#121212] p-4 rounded-2xl border border-zinc-800">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=Nathan+do+Corte&background=18181b&color=D4AF37&bold=true" class="w-10 h-10 rounded-xl border border-zinc-800">
                        <div>
                            <p class="text-[10px] font-black uppercase text-white leading-none tracking-tighter">Nathan do Corte</p>
                            <p class="text-[8px] text-zinc-600 uppercase mt-1 font-bold">Barbeiro Mestre</p>
                        </div>
                    </div>
                    <div class="text-[#D4AF37] text-[10px] font-black italic">5.0</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxVisitors = document.getElementById('visitorsChart').getContext('2d');
        new Chart(ctxVisitors, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    data: @json($visitorsData),
                    borderColor: '#D4AF37',
                    backgroundColor: 'rgba(212, 175, 55, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { display: false }, x: { grid: { display: false }, ticks: { color: '#52525b', font: { size: 10 } } } }
            }
        });

        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'doughnut',
            data: {
                labels: ['Serviços', 'Produtos'],
                datasets: [{
                    data: [{{ $receitaServicos }}, {{ $receitaProdutos }}],
<<<<<<< HEAD
                    backgroundColor: ['#D4AF37', '#3f3f46'],
=======
                    backgroundColor: ['#D4AF37', '#1a1a1a'],
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                    borderWidth: 0
                }]
            },
            options: { cutout: '80%', plugins: { legend: { display: false } } }
        });
    </script>
<<<<<<< HEAD
@endsection
=======
@endsection
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
