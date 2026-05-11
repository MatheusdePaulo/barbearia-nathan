@extends('layouts.admin')

@section('content')
    {{-- Container com padding adaptável --}}
    <div class="p-4 sm:p-8 space-y-6 lg:space-y-8 bg-[#050505] min-h-screen" x-data="{ openModal: false, activeProduct: {} }">

        <div class="text-center lg:text-left">
            <h1 class="text-xl sm:text-2xl font-black italic text-white uppercase tracking-tighter">Gestão de Inventário</h1>
            <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-1">Ajuste de preços e controle de estoque</p>
        </div>

        <div class="bg-[#121212] border border-zinc-800 rounded-3xl overflow-hidden shadow-2xl mb-10">
            <div class="p-4 lg:p-6">

                {{-- TABELA DESKTOP (Invisível abaixo de 1024px) --}}
                <div class="hidden lg:block">
                    <table class="w-full text-left">
                        <thead class="text-zinc-500 uppercase tracking-widest text-[10px] border-b border-zinc-800">
                        <tr>
                            <th class="pb-4 font-bold">Produto</th>
                            <th class="pb-4 font-bold">Preço Atual</th>
                            <th class="pb-4 font-bold text-center">Em Estoque</th>
                            <th class="pb-4 font-bold text-right">Ações</th>
                        </tr>
                        </thead>
                        <tbody class="text-zinc-300 text-xs">
                        @foreach($products as $produto)
                            <tr class="border-b border-zinc-800/50 hover:bg-zinc-900/30 transition-all group">
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('images/' . $produto->image) }}" class="w-12 h-12 rounded-xl border border-zinc-800 object-cover shadow-lg">
                                        <span class="font-bold italic uppercase tracking-tighter">{{ $produto->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 text-[#D4AF37] font-black italic text-sm">R$ {{ number_format($produto->price, 2, ',', '.') }}</td>
                                <td class="py-4 text-center">
                                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase {{ $produto->stock > 5 ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }}">
                                        {{ $produto->stock }} Unidades
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <button @click="openModal = true; activeProduct = { id: '{{ $produto->id }}', name: '{{ $produto->name }}', price: '{{ $produto->price }}', stock: '{{ $produto->stock }}' }"
                                            class="bg-zinc-800 group-hover:bg-[#D4AF37] group-hover:text-black px-4 py-2.5 rounded-xl text-[9px] font-black uppercase transition-all tracking-widest">
                                        Ajustar Valores
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- CARDS MOBILE (Visível apenas em Mobile/Tablet) --}}
                <div class="lg:hidden grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($products as $produto)
                        <div class="bg-zinc-900/30 border border-zinc-800 rounded-2xl p-4 flex flex-col gap-4">
                            <div class="flex gap-4">
                                <img src="{{ asset('images/' . $produto->image) }}" class="w-16 h-16 rounded-xl border border-zinc-800 object-cover shadow-2xl">
                                <div class="flex-1">
                                    <h3 class="text-white font-black italic uppercase text-xs leading-tight mb-1">{{ $produto->name }}</h3>
                                    <p class="text-[#D4AF37] font-black italic text-sm">R$ {{ number_format($produto->price, 2, ',', '.') }}</p>
                                    <div class="mt-2">
                                        <span class="text-[8px] font-black uppercase px-2 py-0.5 rounded {{ $produto->stock > 5 ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }}">
                                            Estoque: {{ $produto->stock }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <button @click="openModal = true; activeProduct = { id: '{{ $produto->id }}', name: '{{ $produto->name }}', price: '{{ $produto->price }}', stock: '{{ $produto->stock }}' }"
                                    class="w-full bg-zinc-800 text-white py-3.5 rounded-xl text-[9px] font-black uppercase tracking-widest border border-zinc-700 active:bg-[#D4AF37] active:text-black transition-all">
                                <i class="fas fa-edit mr-2"></i> Ajustar Produto
                            </button>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

        {{-- MODAL DE AJUSTE RESPONSIVO --}}
        <div x-show="openModal"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm"
             x-transition.opacity
             x-cloak>

            <div class="bg-[#121212] border border-zinc-800 p-6 sm:p-8 rounded-[2.5rem] w-full max-w-md shadow-2xl relative" @click.away="openModal = false">

                <h2 class="text-white font-black italic uppercase tracking-tighter text-xl mb-1" x-text="activeProduct.name"></h2>
                <p class="text-zinc-500 text-[9px] font-bold uppercase tracking-widest mb-6">Alteração de preço e estoque</p>

                <form :action="`/admin/products/${activeProduct.id}`" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">Novo Preço (R$)</label>
                        <input type="number" step="0.01" name="price" x-model="activeProduct.price"
                               class="w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#D4AF37] focus:ring-0 transition-all font-mono">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">Quantidade em Estoque</label>
                        <input type="number" name="stock" x-model="activeProduct.stock"
                               class="w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#D4AF37] focus:ring-0 transition-all font-mono">
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="button" @click="openModal = false"
                                class="order-2 sm:order-1 flex-1 bg-zinc-900 text-zinc-500 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest border border-zinc-800">Cancelar</button>
                        <button type="submit"
                                class="order-1 sm:order-2 flex-1 bg-[#D4AF37] text-black py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-[#D4AF37]/10">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
