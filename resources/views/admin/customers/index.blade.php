@extends('layouts.admin')

@section('content')
    <div class="flex flex-col min-h-screen lg:h-screen bg-[#050505]">

        <div class="p-4 sm:p-8 flex-1 overflow-y-auto custom-scroll space-y-6 lg:space-y-8">

            <div class="flex flex-col gap-6">
                {{-- Título e Contador --}}
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-black italic text-white uppercase tracking-tighter text-center lg:text-left">Gestão de Clientes</h1>
                        <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-1 text-center lg:text-left">Total de {{ $customers->count() }} clientes encontrados</p>
                    </div>

                    {{-- Filtros Rápidos (7 dias, hoje, etc) --}}
                    <div class="flex gap-2 bg-zinc-900/50 p-1 rounded-xl border border-zinc-800 overflow-x-auto no-scrollbar justify-center lg:justify-start">
                        <a href="{{ route('admin.customers') }}" class="whitespace-nowrap px-4 py-2 rounded-lg text-[10px] font-black uppercase transition-all {{ !$filter ? 'bg-[#D4AF37] text-black' : 'text-zinc-500 hover:text-white' }}">Todos</a>
                        <a href="{{ route('admin.customers', ['filter' => 'hoje']) }}" class="whitespace-nowrap px-4 py-2 rounded-lg text-[10px] font-black uppercase transition-all {{ $filter == 'hoje' ? 'bg-[#D4AF37] text-black' : 'text-zinc-500 hover:text-white' }}">Hoje</a>
                        <a href="{{ route('admin.customers', ['filter' => 'amanha']) }}" class="whitespace-nowrap px-4 py-2 rounded-lg text-[10px] font-black uppercase transition-all {{ $filter == 'amanha' ? 'bg-[#D4AF37] text-black' : 'text-zinc-500 hover:text-white' }}">Amanhã</a>
                        <a href="{{ route('admin.customers', ['filter' => 'semana']) }}" class="whitespace-nowrap px-4 py-2 rounded-lg text-[10px] font-black uppercase transition-all {{ $filter == 'semana' ? 'bg-[#D4AF37] text-black' : 'text-zinc-500 hover:text-white' }}">7 Dias</a>
                    </div>
                </div>

                {{-- CAMPO DE PESQUISA POR NOME --}}
                <div class="max-w-xl mx-auto lg:mx-0 w-full">
                    <form action="{{ route('admin.customers') }}" method="GET" class="relative group">
                        {{-- Mantém o filtro de data ativo na busca se houver --}}
                        @if($filter) <input type="hidden" name="filter" value="{{ $filter }}"> @endif

                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none transition-all group-focus-within:text-[#D4AF37]">
                            <i class="fas fa-search text-xs"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ENCONTRAR CLIENTE POR NOME..."
                               class="w-full bg-[#121212] border border-zinc-800 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl py-4 pl-12 pr-4 focus:border-[#D4AF37] focus:ring-0 outline-none transition-all placeholder-zinc-700">

                        @if(request('search'))
                            <a href="{{ route('admin.customers', ['filter' => $filter]) }}" class="absolute inset-y-0 right-4 flex items-center text-zinc-600 hover:text-red-500">
                                <i class="fas fa-times-circle"></i>
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="bg-[#121212] border border-zinc-800 rounded-3xl overflow-hidden shadow-2xl mb-10">
                <div class="p-4 sm:p-6">

                    <table class="hidden lg:table w-full text-left border-separate border-spacing-y-2">
                        <thead class="text-zinc-500 uppercase tracking-widest text-[10px]">
                        <tr>
                            <th class="pb-4 px-4 font-bold">Cliente</th>
                            <th class="pb-4 px-4 font-bold">WhatsApp</th>
                            <th class="pb-4 px-4 font-bold">Agendamento & Status</th>
                            <th class="pb-4 px-4 text-right font-bold">Ações Rápidas</th>
                        </tr>
                        </thead>
                        <tbody class="text-zinc-300 text-xs">
                        @forelse($customers as $cliente)
                            <tr class="bg-zinc-900/20 hover:bg-zinc-900/50 transition-all group">
                                <td class="py-4 px-4 rounded-l-2xl border-y border-l border-zinc-800/50 group-hover:border-[#D4AF37]/30">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-zinc-800 border border-zinc-700 flex items-center justify-center text-[10px] font-black text-[#D4AF37]">
                                            {{ strtoupper(substr($cliente->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold italic uppercase leading-none">{{ $cliente->name }}</p>
                                            <p class="text-[9px] text-zinc-600 mt-1 lowercase">{{ $cliente->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 border-y border-zinc-800/50 group-hover:border-[#D4AF37]/30 font-mono text-[#D4AF37]">
                                    {{ $cliente->whatsapp }}
                                </td>
                                <td class="py-4 px-4 border-y border-zinc-800/50 group-hover:border-[#D4AF37]/30">
                                    @if($cliente->next_date)
                                        <div class="flex flex-col gap-2">
                                            <div class="text-zinc-400 text-[10px]">
                                                <span class="text-white font-bold">{{ date('d/m', strtotime($cliente->next_date)) }}</span>
                                                às <span class="text-[#D4AF37] font-bold">{{ $cliente->next_time }}</span>
                                            </div>
                                            <div class="flex gap-2">
                                                <form action="{{ route('admin.appointments.updateStatus', $cliente->appointment_id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="finished">
                                                    <button type="submit" class="text-[8px] bg-green-500/10 text-green-500 border border-green-500/20 px-2 py-0.5 rounded hover:bg-green-500 hover:text-black transition-all font-black uppercase tracking-tighter">Concluído</button>
                                                </form>
                                                <form action="{{ route('admin.appointments.updateStatus', $cliente->appointment_id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="canceled">
                                                    <button type="submit" class="text-[8px] bg-red-500/10 text-red-500 border border-red-500/20 px-2 py-0.5 rounded hover:bg-red-500 hover:text-black transition-all font-black uppercase tracking-tighter">Faltou</button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <span class="opacity-30 italic text-[10px]">Sem reservas</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right rounded-r-2xl border-y border-r border-zinc-800/50 group-hover:border-[#D4AF37]/30">
                                    <div class="flex justify-end gap-2">
                                        <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $cliente->whatsapp) }}" target="_blank" class="bg-green-500/10 hover:bg-green-500 text-green-500 hover:text-black w-8 h-8 rounded-lg flex items-center justify-center transition-all border border-green-500/20">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        <a href="{{ route('admin.customers.show', $cliente->id) }}" class="bg-zinc-800 hover:bg-[#D4AF37] text-zinc-400 hover:text-black w-8 h-8 rounded-lg flex items-center justify-center transition-all border border-zinc-700">
                                            <i class="fas fa-eye text-[10px]"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-12 text-center text-zinc-600 italic">Nenhum cliente encontrado.</td></tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="lg:hidden space-y-4">
                        @forelse($customers as $cliente)
                            <div class="bg-zinc-900/30 border border-zinc-800 p-4 rounded-2xl space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-zinc-800 border border-zinc-700 flex items-center justify-center text-xs font-black text-[#D4AF37]">
                                            {{ strtoupper(substr($cliente->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black italic uppercase text-white leading-none">{{ $cliente->name }}</p>
                                            <p class="text-[10px] text-[#D4AF37] font-mono mt-1">{{ $cliente->whatsapp }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $cliente->whatsapp) }}" target="_blank" class="w-9 h-9 rounded-xl bg-green-500/10 text-green-500 flex items-center justify-center border border-green-500/20">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        <a href="{{ route('admin.customers.show', $cliente->id) }}" class="w-9 h-9 rounded-xl bg-zinc-800 text-zinc-400 flex items-center justify-center border border-zinc-700">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="bg-black/20 p-3 rounded-xl border border-zinc-800/50 flex flex-col gap-3">
                                    @if($cliente->next_date)
                                        <div class="flex justify-between items-center">
                                            <span class="text-[9px] uppercase font-black text-zinc-500">Próximo:</span>
                                            <span class="text-[10px] font-black text-white italic">
                                                {{ date('d/m', strtotime($cliente->next_date)) }} às {{ $cliente->next_time }}
                                            </span>
                                        </div>
                                        <div class="flex gap-2">
                                            <form action="{{ route('admin.appointments.updateStatus', $cliente->appointment_id) }}" method="POST" class="flex-1">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="finished">
                                                <button type="submit" class="w-full py-2 bg-green-500/10 text-green-500 border border-green-500/20 rounded-lg text-[9px] font-black uppercase tracking-widest">Concluído</button>
                                            </form>
                                            <form action="{{ route('admin.appointments.updateStatus', $cliente->appointment_id) }}" method="POST" class="flex-1">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="canceled">
                                                <button type="submit" class="w-full py-2 bg-red-500/10 text-red-500 border border-red-500/20 rounded-lg text-[9px] font-black uppercase tracking-widest">Faltou</button>
                                            </form>
                                        </div>
                                    @else
                                        <p class="text-center text-[9px] text-zinc-600 uppercase font-black italic">Sem reservas pendentes</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-center py-10 text-zinc-600 italic text-sm uppercase font-black tracking-widest">Nenhum cliente encontrado</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
