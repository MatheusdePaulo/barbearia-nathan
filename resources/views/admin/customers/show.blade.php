@extends('layouts.admin')

@section('content')
    <div class="p-8 max-w-5xl mx-auto space-y-8 h-full overflow-y-auto custom-scroll">

        <!-- Header com botão de voltar -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.customers') }}" class="flex items-center gap-2 text-zinc-500 hover:text-[#D4AF37] transition-all group">
                <div class="w-8 h-8 rounded-full bg-zinc-900 border border-zinc-800 flex items-center justify-center group-hover:border-[#D4AF37]/50">
                    <i class="fas fa-arrow-left text-[10px]"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.2em]">Voltar para lista</span>
            </a>
        </div>

        <!-- Card de Perfil Principal -->
        <div class="bg-[#121212] border border-zinc-800 rounded-[2.5rem] p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8 opacity-5">
                <i class="fas fa-user text-9xl"></i>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
                <div class="w-24 h-24 rounded-full bg-zinc-900 border-2 border-[#D4AF37] shadow-[0_0_30px_rgba(212,175,55,0.15)] flex items-center justify-center text-3xl font-black text-[#D4AF37]">
                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-4xl font-black italic text-white uppercase tracking-tighter">{{ $customer->name }}</h1>
                    <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-2">
                    <span class="text-[#D4AF37] font-mono text-sm flex items-center gap-2">
                        <i class="fab fa-whatsapp"></i> {{ $customer->whatsapp }}
                    </span>
                        <span class="text-zinc-500 font-mono text-sm flex items-center gap-2">
                        <i class="far fa-envelope"></i> {{ $customer->email }}
                    </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de Status Rápidos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-gradient-to-br from-[#121212] to-zinc-900 border border-zinc-800 p-6 rounded-3xl">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-[#D4AF37]/10 flex items-center justify-center text-[#D4AF37]">
                        <i class="fas fa-cut"></i>
                    </div>
                    <div>
                        <p class="text-zinc-500 text-[10px] uppercase font-black tracking-widest">Total de Cortes</p>
                        <p class="text-2xl font-black text-white mt-1">{{ $totalCortes }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-[#121212] to-zinc-900 border border-zinc-800 p-6 rounded-3xl">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center text-red-500">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <div>
                        <p class="text-zinc-500 text-[10px] uppercase font-black tracking-widest">Faltas (No-Show)</p>
                        <p class="text-2xl font-black text-white mt-1">{{ $naoCompareceu }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Histórico Refinada -->
        <div class="bg-[#121212] border border-zinc-800 rounded-[2rem] overflow-hidden shadow-2xl">
            <div class="px-8 py-6 border-b border-zinc-800 flex items-center justify-between bg-zinc-900/30">
                <h3 class="text-white font-black uppercase italic text-sm tracking-widest">Histórico de Atendimentos</h3>
                <i class="fas fa-history text-zinc-700"></i>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                    <tr class="text-zinc-600 text-[9px] uppercase font-black tracking-[0.2em]">
                        <th class="px-8 py-4">Data</th>
                        <th class="px-8 py-4">Serviço Realizado</th>
                        <th class="px-8 py-4 text-right">Status</th>
                    </tr>
                    </thead>
                    <tbody class="text-zinc-400 text-xs">
                    @forelse($customer->appointments as $app)
                        <tr class="border-t border-zinc-800/50 hover:bg-zinc-900/50 transition-all">
                            <td class="px-8 py-5 font-mono">{{ date('d/m/Y', strtotime($app->date)) }}</td>
                            <td class="px-8 py-5 font-bold text-zinc-200">{{ $app->service->name }}</td>
                            <td class="px-8 py-5 text-right">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $app->status == 'confirmed' ? 'bg-green-500/10 text-green-500' : 'bg-zinc-800 text-zinc-500' }}">
                                {{ $app->status }}
                            </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-8 py-10 text-center italic text-zinc-600">Nenhum histórico disponível.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
