@extends('layouts.admin')

@section('content')
    {{-- Mantive seu x-data original --}}
    <div class="p-4 sm:p-8 space-y-6 lg:space-y-8 bg-[#050505] min-h-screen" x-data="{ openModal: false, activeService: {} }">

        {{-- TÍTULO RESPONSIVO --}}
        <div class="text-center lg:text-left">
            <h1 class="text-xl sm:text-2xl font-black italic text-white uppercase tracking-tighter">Gestão de Serviços</h1>
            <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-1">Controle de preços e tempo de execução</p>
        </div>

        <div class="bg-[#121212] border border-zinc-800 rounded-3xl overflow-hidden shadow-2xl mb-10">
            <div class="p-4 lg:p-6">

                {{-- TABELA DESKTOP (Invisível no Mobile/Tablet) --}}
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
                                <td class="py-6 font-black italic uppercase tracking-tighter text-sm">{{ $servico->name }}</td>
                                <td class="py-6 text-zinc-500 font-bold uppercase text-[10px]">{{ $servico->duration }} min</td>
                                <td class="py-6 text-[#D4AF37] font-black italic text-sm">R$ {{ number_format($servico->price, 2, ',', '.') }}</td>
                                <td class="py-6 text-right">
                                    <button @click="openModal = true; activeService = { id: '{{ $servico->id }}', name: '{{ $servico->name }}', price: '{{ $servico->price }}', duration: '{{ $servico->duration }}' }"
                                            class="bg-zinc-800 group-hover:bg-[#D4AF37] group-hover:text-black px-6 py-2.5 rounded-xl text-[9px] font-black uppercase transition-all tracking-widest">
                                        Ajustar Valores
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- CARDS MOBILE (Visível apenas no Mobile/Tablet) --}}
                <div class="lg:hidden space-y-4">
                    @foreach($services as $servico)
                        <div class="bg-zinc-900/30 border border-zinc-800 rounded-2xl p-5 flex flex-col gap-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-white font-black italic uppercase text-sm leading-none">{{ $servico->name }}</h3>
                                    <span class="text-[9px] text-zinc-500 font-bold uppercase tracking-widest mt-2 block italic">{{ $servico->duration }} MINUTOS DE EXECUÇÃO</span>
                                </div>
                                <span class="text-[#D4AF37] font-black italic text-sm">R$ {{ number_format($servico->price, 2, ',', '.') }}</span>
                            </div>

                            <button @click="openModal = true; activeService = { id: '{{ $servico->id }}', name: '{{ $servico->name }}', price: '{{ $servico->price }}', duration: '{{ $servico->duration }}' }"
                                    class="w-full bg-zinc-800 text-white py-4 rounded-xl text-[10px] font-black uppercase tracking-widest border border-zinc-700 active:bg-[#D4AF37] active:text-black transition-all">
                                <i class="fas fa-sliders-h mr-2"></i> Ajustar Valores
                            </button>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

        {{-- MODAL DE AJUSTE RESPONSIVO --}}
        <div x-show="openModal"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-cloak>

            <div class="bg-[#121212] border border-zinc-800 p-6 sm:p-8 rounded-[2.5rem] w-full max-w-md shadow-2xl relative" @click.away="openModal = false">

                {{-- Botão Fechar no Topo (Mobile Friendly) --}}
                <button @click="openModal = false" class="absolute top-6 right-6 text-zinc-500 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>

                <h2 class="text-[#D4AF37] font-black italic uppercase tracking-tighter text-xl mb-8 pr-8" x-text="activeService.name"></h2>

                <form :action="`/admin/servicos/${activeService.id}`" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 tracking-widest italic">Preço do Serviço (R$)</label>
                        <input type="number" step="0.01" name="price" x-model="activeService.price"
                               class="w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#D4AF37] focus:ring-0 transition-all font-mono">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 tracking-widest italic">Duração (minutos)</label>
                        <input type="number" name="duration" x-model="activeService.duration"
                               class="w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#D4AF37] focus:ring-0 transition-all font-mono">
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="button" @click="openModal = false"
                                class="order-2 sm:order-1 flex-1 bg-zinc-900 text-zinc-500 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest border border-zinc-800">Cancelar</button>
                        <button type="submit"
                                class="order-1 sm:order-2 flex-1 bg-[#D4AF37] text-black py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-[#D4AF37]/10">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
