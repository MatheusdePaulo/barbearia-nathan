@extends('layouts.admin')

@section('content')
    <div class="flex flex-col min-h-screen lg:h-screen bg-[#050505]" x-data="{ openDraw: false }">

        <div class="p-4 sm:p-8 flex-1 overflow-y-auto custom-scroll space-y-6 lg:space-y-8">

            <div class="flex flex-col gap-6">
                {{-- Alerta de Vencedores --}}
                @if(session('winners'))
                    <div class="bg-[#D4AF37]/20 border border-[#D4AF37] p-6 rounded-[2.5rem] animate-bounce shadow-[0_0_50px_rgba(212,175,55,0.2)]">
                        <h2 class="text-[#D4AF37] font-black italic uppercase text-center text-lg mb-2">🎉 Ganhadores do Sorteio!</h2>
                        <div class="flex flex-wrap justify-center gap-4">
                            @foreach(session('winners') as $winner)
                                <span class="bg-black text-white px-4 py-2 rounded-full font-black uppercase text-[10px] border border-[#D4AF37]">
                                    {{ is_object($winner) ? $winner->name : $winner['name'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Título e Botões de Ação --}}
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-black italic text-white uppercase tracking-tighter text-center lg:text-left">Gestão de Clientes</h1>
                        <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-1 text-center lg:text-left">Total de {{ $customers->count() }} clientes encontrados</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button @click="openDraw = true" class="bg-[#D4AF37] text-black px-6 py-3 rounded-xl text-[10px] font-black uppercase shadow-lg shadow-[#D4AF37]/10 flex items-center justify-center gap-2 hover:scale-105 transition-all">
                            <i class="fas fa-trophy"></i> Realizar Sorteio
                        </button>

                        {{-- Filtros Rápidos --}}
                        <div class="flex gap-2 bg-zinc-900/50 p-1 rounded-xl border border-zinc-800 overflow-x-auto no-scrollbar justify-center lg:justify-start">
                            <a href="{{ route('admin.customers') }}" class="whitespace-nowrap px-4 py-2 rounded-lg text-[10px] font-black uppercase transition-all {{ !$filter ? 'bg-zinc-800 text-white' : 'text-zinc-500 hover:text-white' }}">Todos</a>
                            <a href="{{ route('admin.customers', ['filter' => 'hoje']) }}" class="whitespace-nowrap px-4 py-2 rounded-lg text-[10px] font-black uppercase transition-all {{ $filter == 'hoje' ? 'bg-[#D4AF37] text-black' : 'text-zinc-500 hover:text-white' }}">Hoje</a>
                            <a href="{{ route('admin.customers', ['filter' => 'amanha']) }}" class="whitespace-nowrap px-4 py-2 rounded-lg text-[10px] font-black uppercase transition-all {{ $filter == 'amanha' ? 'bg-[#D4AF37] text-black' : 'text-zinc-500 hover:text-white' }}">Amanhã</a>
                            <a href="{{ route('admin.customers', ['filter' => 'semana']) }}" class="whitespace-nowrap px-4 py-2 rounded-lg text-[10px] font-black uppercase transition-all {{ $filter == 'semana' ? 'bg-[#D4AF37] text-black' : 'text-zinc-500 hover:text-white' }}">7 Dias</a>
                        </div>
                    </div>
                </div>

                {{-- CAMPO DE PESQUISA --}}
                <div class="max-w-xl mx-auto lg:mx-0 w-full">
                    <form action="{{ route('admin.customers') }}" method="GET" class="relative group">
                        @if($filter) <input type="hidden" name="filter" value="{{ $filter }}"> @endif
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none transition-all group-focus-within:text-[#D4AF37]">
                            <i class="fas fa-search text-xs"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ENCONTRAR CLIENTE POR NOME..."
                               class="w-full bg-[#121212] border border-zinc-800 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl py-4 pl-12 pr-4 focus:border-[#D4AF37] focus:ring-0 outline-none transition-all placeholder-zinc-700">
                    </form>
                </div>
            </div>

            {{-- Tabela de Clientes --}}
            <div class="bg-[#121212] border border-zinc-800 rounded-3xl overflow-hidden shadow-2xl mb-10">
                <div class="p-4 sm:p-6 overflow-x-auto custom-scroll">
                    <table class="w-full text-left border-separate border-spacing-y-2 min-w-[700px]">
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
                                        <div class="w-9 h-9 rounded-full bg-zinc-800 border border-zinc-700 flex items-center justify-center text-[10px] font-black text-[#D4AF37] shrink-0">
                                            {{ strtoupper(substr($cliente->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold italic uppercase leading-none">{{ $cliente->name }}</p>
                                            <p class="text-[9px] text-zinc-600 mt-1 lowercase">{{ $cliente->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 border-y border-zinc-800/50 group-hover:border-[#D4AF37]/30 font-mono text-[#D4AF37] whitespace-nowrap">
                                    {{ $cliente->whatsapp }}
                                </td>
                                <td class="py-4 px-4 border-y border-zinc-800/50 group-hover:border-[#D4AF37]/30">
                                    @if($cliente->next_date)
                                        <div class="flex flex-col gap-2 min-w-[120px]">
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
                                    @业务else
                                        <span class="opacity-30 italic text-[10px]">Sem reservas</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right rounded-r-2xl border-y border-r border-zinc-800/50 group-hover:border-[#D4AF37]/30">
                                    @php
                                        $phone      = preg_replace('/[^0-9]/', '', $cliente->whatsapp ?? '');
                                        $reviewLink = $cliente->whatsapp
                                            ? \URL::temporarySignedRoute('reviews.form', now()->addDays(7), ['user_id' => $cliente->id])
                                            : null;
                                        $reviewMsg  = $reviewLink
                                            ? urlencode("Olá {$cliente->name}! 😊 Adoraria saber como foi sua experiência na Nathan do Corte! Deixa sua avaliação e garante 5% de desconto no próximo corte 👇\n{$reviewLink}")
                                            : null;
                                    @endphp
                                    <div class="flex justify-end gap-2">
                                        {{-- WhatsApp direto --}}
                                        @if($phone)
                                            <a href="https://wa.me/55{{ $phone }}" target="_blank"
                                               class="bg-green-500/10 hover:bg-green-500 text-green-500 hover:text-black w-8 h-8 rounded-lg flex items-center justify-center transition-all border border-green-500/20"
                                               title="Abrir WhatsApp">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                        @else
                                            <span class="bg-zinc-900 text-zinc-700 w-8 h-8 rounded-lg flex items-center justify-center border border-zinc-800 cursor-not-allowed" title="Sem WhatsApp">
                                                <i class="fab fa-whatsapp text-[10px]"></i>
                                            </span>
                                        @endif

                                        {{-- Pedir avaliação via WhatsApp --}}
                                        @if($phone && $reviewMsg)
                                            <a href="https://wa.me/55{{ $phone }}?text={{ $reviewMsg }}" target="_blank"
                                               class="bg-[#D4AF37]/10 hover:bg-[#D4AF37] text-[#D4AF37] hover:text-black w-8 h-8 rounded-lg flex items-center justify-center transition-all border border-[#D4AF37]/30"
                                               title="Pedir avaliação">
                                                <i class="fas fa-star text-[10px]"></i>
                                            </a>
                                        @else
                                            <span class="bg-zinc-900 text-zinc-700 w-8 h-8 rounded-lg flex items-center justify-center border border-zinc-800 cursor-not-allowed" title="Sem WhatsApp para enviar">
                                                <i class="fas fa-star text-[10px]"></i>
                                            </span>
                                        @endif

                                        {{-- Ver perfil --}}
                                        <a href="{{ route('admin.customers.show', $cliente->id) }}"
                                           class="bg-zinc-800 hover:bg-zinc-700 text-zinc-400 hover:text-white w-8 h-8 rounded-lg flex items-center justify-center transition-all border border-zinc-700"
                                           title="Ver perfil">
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
                </div>

                @if($customers->hasPages())
                    <div class="px-6 py-4 border-t border-zinc-800 flex justify-center">
                        {{ $customers->links('pagination::simple-tailwind') }}
                    </div>
                @endif
            </div>
        </div>

        {{-- MODAL DE SORTEIO --}}
        <div x-show="openDraw" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/95 backdrop-blur-sm" x-cloak>
            <div class="bg-[#121212] border border-zinc-800 p-8 rounded-[2.5rem] w-full max-w-md shadow-2xl relative" @click.away="openDraw = false">
                <h2 class="text-white font-black italic uppercase tracking-tighter text-xl mb-6 flex items-center gap-3">
                    <i class="fas fa-star text-[#D4AF37]"></i> Configurar Sorteio
                </h2>

                <form action="{{ route('admin.customers.draw') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-4">
                        <label class="text-[10px] font-black uppercase text-zinc-500 block ml-2">Público do Sorteio</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="all" class="peer hidden" checked>
                                <div class="p-4 bg-zinc-900 border border-zinc-800 rounded-2xl text-center peer-checked:border-[#D4AF37] peer-checked:text-[#D4AF37] transition-all">
                                    <span class="text-[9px] font-black uppercase">Todos</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="recent" class="peer hidden">
                                <div class="p-4 bg-zinc-900 border border-zinc-800 rounded-2xl text-center peer-checked:border-[#D4AF37] peer-checked:text-[#D4AF37] transition-all">
                                    <span class="text-[9px] font-black uppercase">Último Mês</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-zinc-500 block ml-2">Qtd de Ganhadores</label>
                        <input type="number" name="quantity" value="1" min="1" max="10"
                               class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-white text-xs focus:border-[#D4AF37] outline-none font-black">
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="openDraw = false" class="flex-1 bg-zinc-800 text-white py-4 rounded-2xl font-black text-[10px] uppercase italic">Cancelar</button>
                        <button type="submit" class="flex-[2] bg-[#D4AF37] text-black py-4 rounded-2xl font-black text-[10px] uppercase italic shadow-lg shadow-[#D4AF37]/10">Sortear Agora</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
