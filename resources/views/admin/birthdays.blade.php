@extends('layouts.admin')

@section('content')
    <div class="p-4 sm:p-8 space-y-6 lg:space-y-8 bg-[#050505] min-h-screen">

        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-center sm:text-left">
                <h1 class="text-xl sm:text-2xl font-black italic text-white uppercase tracking-tighter">Aniversariantes de Hoje</h1>
                <p class="text-zinc-500 text-[10px] sm:text-xs font-bold uppercase tracking-widest mt-1">Gere lealdade enviando um presente</p>
            </div>
            <div class="bg-[#D4AF37]/10 px-4 py-2 rounded-xl border border-[#D4AF37]/20 w-full sm:w-auto text-center">
                <span class="text-[#D4AF37] font-black italic text-xs sm:text-sm">{{ now()->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
            @forelse($aniversariantes as $cliente)
                <div class="bg-[#121212] p-6 rounded-[2.5rem] border border-zinc-800 shadow-xl border-l-4 border-l-[#D4AF37] hover:border-[#D4AF37]/40 transition-all flex flex-col justify-between group">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($cliente->name) }}&background=18181b&color=D4AF37&bold=true"
                             class="w-12 h-12 rounded-2xl border border-zinc-800 group-hover:border-[#D4AF37]/50 transition-colors">
                        <div class="overflow-hidden">
                            <h3 class="text-white font-black uppercase text-sm italic truncate">{{ $cliente->name }}</h3>
                            <p class="text-zinc-600 text-[9px] font-bold uppercase tracking-tighter truncate">{{ $cliente->email }}</p>
                        </div>
                    </div>

                    @php
                        $numero = preg_replace('/[^0-9]/', '', $cliente->whatsapp ?? '');
                        $primeiroNome = explode(' ', $cliente->name)[0];
                        $mensagem = "Fala, " . $primeiroNome . "! 🎈 A Barber Nathan passando para te dar os parabéns! Para comemorar seu dia, você tem um desconto especial reservado aqui na barbearia. Vamos dar aquele tapa no visual hoje?";
                        $urlWhats = "https://wa.me/55" . $numero . "?text=" . urlencode($mensagem);
                    @endphp

                    <a href="{{ $urlWhats }}" target="_blank"
                       class="w-full bg-green-500/10 hover:bg-green-500 text-green-500 hover:text-black py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-3 transition-all active:scale-95 shadow-lg shadow-green-500/5">
                        <i class="fab fa-whatsapp text-base"></i> Enviar Presente
                    </a>
                </div>
            @empty
                <div class="col-span-full py-24 text-center bg-[#121212]/50 rounded-[3rem] border border-dashed border-zinc-800/50 flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-zinc-900 rounded-full flex items-center justify-center mb-6 border border-zinc-800">
                        <i class="fas fa-calendar-day text-zinc-700 text-3xl"></i>
                    </div>
                    <p class="text-zinc-500 font-black uppercase text-[10px] tracking-[0.3em] italic">Nenhum aniversariante hoje</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
