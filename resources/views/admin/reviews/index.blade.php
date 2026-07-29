@extends('layouts.admin')

@section('content')
<div class="flex flex-col min-h-screen bg-[#050505]">
    <div class="p-4 sm:p-8 space-y-6 lg:space-y-8">

        {{-- Cabeçalho --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-black italic text-white uppercase tracking-tighter">Avaliações</h1>
                <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-1">{{ $total }} avaliação{{ $total !== 1 ? 'ões' : '' }} no total</p>
            </div>
        </div>

        {{-- Cards de resumo --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-[#121212] border border-zinc-800 rounded-2xl p-6 text-center">
                <p class="text-[10px] font-black uppercase text-zinc-500 tracking-widest mb-2">Média Geral</p>
                <p class="text-4xl font-black text-[#D4AF37]">{{ $average ?? '—' }}</p>
                <p class="text-[10px] text-zinc-600 mt-1 italic">Notas 3, 4 e 5</p>
            </div>
            <div class="bg-[#121212] border border-zinc-800 rounded-2xl p-6 text-center">
                <p class="text-[10px] font-black uppercase text-zinc-500 tracking-widest mb-2">Total</p>
                <p class="text-4xl font-black text-white">{{ $total }}</p>
                <p class="text-[10px] text-zinc-600 mt-1 italic">avaliações</p>
            </div>
        </div>

        {{-- Lista de avaliações --}}
        <div class="bg-[#121212] border border-zinc-800 rounded-3xl overflow-hidden shadow-2xl">
            <div class="p-4 sm:p-6 space-y-4">
                @forelse($reviews as $review)
                    @php
                        $isNegative = $review->rating <= 2;
                        $authorName = $review->user?->name ?? $review->client_name ?? 'Cliente';
                        $whatsapp   = $review->user?->whatsapp ?? null;
                        $waText     = urlencode("Olá {$authorName}! 😊 Obrigado por visitar a Nathan do Corte! Avalie nossa barbearia e ganhe {$reviewCouponPercent}% de desconto no próximo corte 👇 https://nathandocorte.com/avaliar");
                        $waLink     = $whatsapp ? "https://wa.me/55{$whatsapp}?text={$waText}" : null;
                    @endphp

                    <div class="bg-zinc-900/50 border rounded-2xl p-5 flex flex-col sm:flex-row sm:items-start gap-4 {{ $isNegative ? 'border-red-500/30' : 'border-zinc-800' }}">
                        <div class="flex-1 space-y-2">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="font-black text-sm {{ $isNegative ? 'text-red-400' : 'text-white' }} uppercase">{{ $authorName }}</span>
                                @if($isNegative)
                                    <span class="text-[9px] font-black bg-red-500/20 text-red-400 px-2 py-0.5 rounded-full uppercase tracking-widest">Crítica</span>
                                @endif
                            </div>

                            <div class="flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-sm {{ $i <= $review->rating ? 'text-[#D4AF37]' : 'text-zinc-700' }}">★</span>
                                @endfor
                            </div>

                            @if($review->comment)
                                <p class="text-zinc-400 text-sm leading-relaxed italic">"{{ $review->comment }}"</p>
                            @endif

                            <p class="text-zinc-600 text-[10px] font-bold uppercase tracking-widest">
                                {{ $review->created_at->format('d/m/Y') }}
                                @if($review->appointment)
                                    · {{ $review->appointment->service_label }}
                                @endif
                            </p>
                        </div>

                        @if($waLink)
                            <a href="{{ $waLink }}"
                               target="_blank"
                               class="shrink-0 flex items-center gap-2 bg-green-600/10 hover:bg-green-600/20 border border-green-600/30 text-green-400 font-black text-[10px] uppercase tracking-widest px-4 py-2 rounded-xl transition-all">
                                <i class="fab fa-whatsapp text-base"></i> WhatsApp
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-16">
                        <i class="fas fa-star text-3xl text-zinc-700 mb-4"></i>
                        <p class="text-zinc-600 font-black uppercase text-[11px] tracking-widest">Nenhuma avaliação ainda</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
