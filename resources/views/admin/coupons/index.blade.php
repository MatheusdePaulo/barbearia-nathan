@extends('layouts.admin')

@section('content')
<div class="flex flex-col min-h-screen bg-[#050505]"
     x-data="{ openCreate: false, openEdit: false, editCoupon: null }">

    <div class="p-4 sm:p-8 space-y-6 lg:space-y-8">

        {{-- Cabeçalho --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-black italic text-white uppercase tracking-tighter">Cupons</h1>
                <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-1">{{ $coupons->count() }} cupom{{ $coupons->count() !== 1 ? 'ns' : '' }} cadastrado{{ $coupons->count() !== 1 ? 's' : '' }}</p>
            </div>
            <button @click="openCreate = true"
                    class="bg-[#D4AF37] text-black px-6 py-3 rounded-xl text-[10px] font-black uppercase shadow-lg shadow-[#D4AF37]/10 flex items-center gap-2 hover:scale-105 transition-all">
                <i class="fas fa-plus"></i> Novo Cupom
            </button>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 p-4 rounded-2xl font-black uppercase text-[10px] tracking-widest">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabela de cupons --}}
        <div class="bg-[#121212] border border-zinc-800 rounded-3xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-zinc-800">
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-zinc-500 tracking-widest">Código</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-zinc-500 tracking-widest">Desconto</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-zinc-500 tracking-widest">Tipo</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-zinc-500 tracking-widest">Usos</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-zinc-500 tracking-widest">Validade</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-zinc-500 tracking-widest">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-zinc-500 tracking-widest">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/50">
                        @forelse($coupons as $coupon)
                            @php
                                $status = $coupon->status_label;
                                $statusClass = match($status) {
                                    'ativo'      => 'bg-green-500/15 text-green-400 border-green-500/30',
                                    'esgotado'   => 'bg-zinc-500/15 text-zinc-400 border-zinc-500/30',
                                    'expirado'   => 'bg-red-500/15 text-red-400 border-red-500/30',
                                    'desativado' => 'bg-orange-500/15 text-orange-400 border-orange-500/30',
                                    default      => 'bg-zinc-800 text-zinc-500',
                                };
                            @endphp
                            <tr class="hover:bg-zinc-800/20 transition-colors">
                                <td class="px-6 py-4 font-mono font-black text-[#D4AF37] text-sm tracking-widest">{{ $coupon->code }}</td>
                                <td class="px-6 py-4 font-black text-white">{{ $coupon->discount_percent }}%</td>
                                <td class="px-6 py-4">
                                    <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full border
                                        {{ $coupon->type === 'review' ? 'bg-[#D4AF37]/10 text-[#D4AF37] border-[#D4AF37]/30' : 'bg-zinc-800 text-zinc-400 border-zinc-700' }}">
                                        {{ $coupon->type === 'review' ? 'Avaliação' : 'Manual' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-zinc-300 font-bold text-sm">
                                    {{ $coupon->use_count }}
                                    @if($coupon->max_uses > 0)
                                        / {{ $coupon->max_uses }}
                                    @else
                                        / ∞
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-zinc-400 text-xs">
                                    {{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full border {{ $statusClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        {{-- Editar --}}
                                        <button @click="editCoupon = {{ $coupon->toJson() }}; openEdit = true"
                                                class="w-8 h-8 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                                            <i class="fas fa-pen text-xs"></i>
                                        </button>

                                        {{-- Toggle ativo/inativo --}}
                                        <form action="{{ route('admin.coupons.toggle', $coupon->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-all
                                                        {{ $coupon->is_active ? 'bg-orange-500/10 hover:bg-orange-500/20 text-orange-400' : 'bg-green-500/10 hover:bg-green-500/20 text-green-400' }}">
                                                <i class="fas {{ $coupon->is_active ? 'fa-ban' : 'fa-check' }} text-xs"></i>
                                            </button>
                                        </form>

                                        {{-- Excluir --}}
                                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST"
                                              onsubmit="return confirm('Excluir o cupom {{ $coupon->code }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition-all">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <i class="fas fa-ticket-alt text-3xl text-zinc-700 mb-4 block"></i>
                                    <p class="text-zinc-600 font-black uppercase text-[11px] tracking-widest">Nenhum cupom cadastrado</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL: Criar Cupom --}}
    <div x-show="openCreate" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         @keydown.escape.window="openCreate = false">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="openCreate = false"></div>
        <div class="relative bg-[#121212] border border-zinc-800 rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-[#D4AF37]"></div>
            <div class="p-8 space-y-5">
                <div>
                    <p class="text-[9px] font-black uppercase text-[#D4AF37] tracking-widest mb-1">Novo Cupom</p>
                    <h3 class="text-xl font-black italic text-white uppercase tracking-tight">Criar Cupom Manual</h3>
                </div>

                <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">Código <span class="text-zinc-700">(deixe em branco para gerar)</span></label>
                        <input type="text" name="code" placeholder="EX: PROMO10"
                               class="block w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl py-3 px-4 text-white text-sm uppercase font-mono outline-none focus:border-[#D4AF37] placeholder-zinc-700">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">Desconto %</label>
                            <input type="number" name="discount_percent" min="1" max="100" value="10" required
                                   class="block w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl py-3 px-4 text-white text-sm outline-none focus:border-[#D4AF37]">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">Máx. Usos <span class="text-zinc-700">(0=∞)</span></label>
                            <input type="number" name="max_uses" min="0" value="1" required
                                   class="block w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl py-3 px-4 text-white text-sm outline-none focus:border-[#D4AF37]">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">Validade <span class="text-zinc-700">(opcional)</span></label>
                        <input type="date" name="expires_at" style="color-scheme: dark;"
                               class="block w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl py-3 px-4 text-white text-sm outline-none focus:border-[#D4AF37]">
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="openCreate = false"
                                class="flex-1 py-3 bg-zinc-800 hover:bg-zinc-700 text-white font-black uppercase rounded-2xl text-[11px] tracking-widest transition-all">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="flex-1 py-3 bg-[#D4AF37] hover:bg-[#f3ca4a] text-black font-black uppercase rounded-2xl text-[11px] tracking-widest transition-all">
                            Criar Cupom
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: Editar Cupom --}}
    <div x-show="openEdit" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         @keydown.escape.window="openEdit = false">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="openEdit = false"></div>
        <div class="relative bg-[#121212] border border-zinc-800 rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-[#D4AF37]"></div>
            <div class="p-8 space-y-5" x-show="editCoupon">
                <div>
                    <p class="text-[9px] font-black uppercase text-[#D4AF37] tracking-widest mb-1">Editar</p>
                    <h3 class="text-xl font-black italic text-white uppercase tracking-tight" x-text="'Cupom ' + (editCoupon?.code ?? '')"></h3>
                </div>

                <template x-if="editCoupon">
                    <form :action="`/admin/cupons/${editCoupon.id}`" method="POST" class="space-y-4">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">Desconto %</label>
                                <input type="number" name="discount_percent" min="1" max="100"
                                       :value="editCoupon.discount_percent"
                                       class="block w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl py-3 px-4 text-white text-sm outline-none focus:border-[#D4AF37]">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">Máx. Usos</label>
                                <input type="number" name="max_uses" min="0"
                                       :value="editCoupon.max_uses"
                                       class="block w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl py-3 px-4 text-white text-sm outline-none focus:border-[#D4AF37]">
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">Validade</label>
                            <input type="date" name="expires_at" style="color-scheme: dark;"
                                   :value="editCoupon.expires_at ? editCoupon.expires_at.substring(0, 10) : ''"
                                   class="block w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl py-3 px-4 text-white text-sm outline-none focus:border-[#D4AF37]">
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="openEdit = false"
                                    class="flex-1 py-3 bg-zinc-800 hover:bg-zinc-700 text-white font-black uppercase rounded-2xl text-[11px] tracking-widest transition-all">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="flex-1 py-3 bg-[#D4AF37] hover:bg-[#f3ca4a] text-black font-black uppercase rounded-2xl text-[11px] tracking-widest transition-all">
                                Salvar
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>

</div>
@endsection
