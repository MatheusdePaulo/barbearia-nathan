@extends('layouts.admin')

@section('content')
    <div class="p-8 max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-black italic text-white uppercase tracking-tighter">Novo Produto</h1>
            <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-1">Adicione itens à vitrine do Nathan</p>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" class="bg-[#121212] p-8 rounded-3xl border border-zinc-800 space-y-6">
            @csrf

            <div>
                <label class="text-[10px] font-black uppercase text-zinc-500 mb-2 block tracking-widest">Nome do Produto</label>
                <input type="text" name="name" placeholder="Ex: Pomada Matte" class="w-full bg-zinc-900 border-zinc-800 rounded-xl text-white text-sm focus:border-[#D4AF37] focus:ring-0 transition-all">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-[10px] font-black uppercase text-zinc-500 mb-2 block tracking-widest">Preço (R$)</label>
                    <input type="number" step="0.01" name="price" placeholder="45.00" class="w-full bg-zinc-900 border-zinc-800 rounded-xl text-white text-sm focus:border-[#D4AF37] focus:ring-0 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-zinc-500 mb-2 block tracking-widest">Estoque Inicial</label>
                    <input type="number" name="stock" placeholder="10" class="w-full bg-zinc-900 border-zinc-800 rounded-xl text-white text-sm focus:border-[#D4AF37] focus:ring-0 transition-all">
                </div>
            </div>

            <button type="submit" class="w-full bg-[#D4AF37] text-black py-4 rounded-xl font-black text-xs uppercase tracking-[0.2em] hover:scale-[1.02] transition-all shadow-[0_0_20px_rgba(212,175,55,0.2)]">
                Salvar Produto
            </button>
        </form>
    </div>
@endsection
