@extends('layouts.admin')

@section('content')
    {{-- Alpine.js atualizado com os novos campos --}}
    <div class="p-4 sm:p-8 space-y-6 lg:space-y-8 bg-[#050505] min-h-screen" x-data="{ openModal: false, activeService: {} }">

        {{-- TÍTULO RESPONSIVO --}}
        <div class="text-center lg:text-left">
            <h1 class="text-xl sm:text-2xl font-black italic text-white uppercase tracking-tighter">Gestão de Serviços</h1>
            <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-1">Controle de preços e tempo de execução</p>
        </div>

        <div class="bg-[#121212] border border-zinc-800 rounded-3xl overflow-hidden shadow-2xl mb-10">
            <div class="p-4 lg:p-6">

                {{-- TABELA DESKTOP --}}
                <div class="hidden lg:block">
                    <table class="w-full text-left">
                        <thead class="text-zinc-500 uppercase tracking-widest text-[10px] border-b border-zinc-800">
                        <tr>
                            <th class="pb-4 font-bold">Serviço</th>
                            <th class="pb-4 font-bold">Duração Estimada</th>
                            <th class="pb-4 font-bold">Preço Atual</th>
                            <th class="pb-4 font-bold text-right">Ações</th>
                        </tr>
                        </thead>
                        <tbody class="text-zinc-300 text-xs">
                        @foreach($services as $servico)
                            <tr class="border-b border-zinc-800/50 hover:bg-zinc-900/30 transition-all group">
                                <td class="py-6 font-black italic uppercase tracking-tighter text-sm">
                                    <div class="flex items-center gap-2">
                                        {{ $servico->name }}
                                        @if($servico->is_promo)
                                            <span class="bg-[#D4AF37] text-black text-[8px] px-2 py-0.5 rounded-sm not-italic">PROMO</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-6 text-zinc-500 font-bold uppercase text-[10px]">{{ $servico->duration }} min</td>
                                <td class="py-6">
                                    @if($servico->is_promo)
                                        <div class="flex flex-col">
                                            <span class="text-[10px] text-zinc-600 line-through">R$ {{ number_format($servico->price, 2, ',', '.') }}</span>
                                            <span class="text-[#D4AF37] font-black italic text-sm">R$ {{ number_format($servico->promo_price, 2, ',', '.') }}</span>
                                        </div>
                                    @else
                                        <span class="text-white font-black italic text-sm">R$ {{ number_format($servico->price, 2, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="py-6 text-right">
                                    <button @click="openModal = true; activeService = { id: '{{ $servico->id }}', name: '{{ $servico->name }}', price: '{{ $servico->price }}', duration: '{{ $servico->duration }}', is_promo: {{ $servico->is_promo ? 'true' : 'false' }}, promo_price: '{{ $servico->promo_price }}' }"
                                            class="bg-zinc-800 group-hover:bg-[#D4AF37] group-hover:text-black px-6 py-2.5 rounded-xl text-[9px] font-black uppercase transition-all tracking-widest">
                                        Ajustar Valores
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- CARDS MOBILE --}}
                <div class="lg:hidden space-y-4">
                    @foreach($services as $servico)
                        <div class="bg-zinc-900/30 border border-zinc-800 rounded-2xl p-5 flex flex-col gap-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-white font-black italic uppercase text-sm leading-none">{{ $servico->name }}</h3>
                                    <span class="text-[9px] text-zinc-500 font-bold uppercase tracking-widest mt-2 block italic">{{ $servico->duration }} MINUTOS DE EXECUÇÃO</span>
                                </div>
                                <div class="text-right">
                                    @if($servico->is_promo)
                                        <span class="text-[9px] text-zinc-600 line-through block uppercase font-bold">R$ {{ number_format($servico->price, 2, ',', '.') }}</span>
                                        <span class="text-[#D4AF37] font-black italic text-sm">R$ {{ number_format($servico->promo_price, 2, ',', '.') }}</span>
                                    @else
                                        <span class="text-[#D4AF37] font-black italic text-sm">R$ {{ number_format($servico->price, 2, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>

                            <button @click="openModal = true; activeService = { id: '{{ $servico->id }}', name: '{{ $servico->name }}', price: '{{ $servico->price }}', duration: '{{ $servico->duration }}', is_promo: {{ $servico->is_promo ? 'true' : 'false' }}, promo_price: '{{ $servico->promo_price }}' }"
                                    class="w-full bg-zinc-800 text-white py-4 rounded-xl text-[10px] font-black uppercase tracking-widest border border-zinc-700 active:bg-[#D4AF37] active:text-black transition-all">
                                <i class="fas fa-sliders-h mr-2"></i> Ajustar Valores
                            </button>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

        {{-- MODAL DE AJUSTE --}}
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm" x-cloak>
            <div class="bg-[#121212] border border-zinc-800 p-6 sm:p-8 rounded-[2.5rem] w-full max-w-md shadow-2xl relative" @click.away="openModal = false">

                <button @click="openModal = false" class="absolute top-6 right-6 text-zinc-500 hover:text-white"><i class="fas fa-times"></i></button>

                <h2 class="text-[#D4AF37] font-black italic uppercase tracking-tighter text-xl mb-8" x-text="activeService.name"></h2>

                <form :action="`/admin/servicos/${activeService.id}`" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Toggle de Promoção com Visibilidade Reforçada --}}
                    <div class="flex items-center justify-between p-4 bg-zinc-900/50 rounded-2xl border border-zinc-800">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black uppercase text-white italic">Ativar Promoção?</span>
                            <span class="text-[8px] text-zinc-500 uppercase font-bold mt-1">Exclusivo para agendamento online</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_promo" x-model="activeService.is_promo" class="sr-only peer">
                            <div class="w-11 h-6 bg-zinc-700 rounded-full peer 
                                        peer-checked:bg-[#D4AF37] 
                                        after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                        after:bg-white after:border-zinc-300 after:border after:rounded-full 
                                        after:h-5 after:w-5 after:transition-all 
                                        peer-checked:after:translate-x-full peer-checked:after:border-white transition-colors">
                            </div>
                        </label>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 tracking-widest italic">Preço Normal (R$)</label>
                        <input type="number" step="0.01" name="price" x-model="activeService.price" class="w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#D4AF37] outline-none font-mono">
                    </div>

                    {{-- Campo de Preço Promocional (Apenas se o Toggle estiver ativo) --}}
                    <div class="space-y-2" x-show="activeService.is_promo" x-transition>
                        <label class="text-[9px] font-black uppercase text-[#D4AF37] ml-2 tracking-widest italic">Preço Promocional Site (R$)</label>
                        <input type="number" step="0.01" name="promo_price" x-model="activeService.promo_price" class="w-full bg-zinc-900 border-[#D4AF37]/50 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#D4AF37] outline-none font-mono shadow-[0_0_15px_rgba(212,175,55,0.05)]">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 tracking-widest italic">Duração (min)</label>
                        <input type="number" name="duration" x-model="activeService.duration" class="w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#D4AF37] outline-none font-mono">
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="openModal = false" class="flex-1 bg-zinc-900 text-zinc-500 py-4 rounded-2xl font-black text-[10px] uppercase border border-zinc-800">Cancelar</button>
                        <button type="submit" class="flex-1 bg-[#D4AF37] text-black py-4 rounded-2xl font-black text-[10px] uppercase shadow-lg shadow-[#D4AF37]/10">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection