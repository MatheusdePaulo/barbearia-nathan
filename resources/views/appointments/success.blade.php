<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento | Barber Nathan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#050505] text-white flex items-center justify-center min-h-screen p-6">

<div x-data="pagamento()" x-init="init()"
     class="max-w-md w-full p-8 bg-[#121212] border border-zinc-800 rounded-3xl text-center shadow-2xl transition-all duration-500">

    <!-- ÍCONE DINÂMICO -->
    <div class="mb-6 flex justify-center">
        <template x-if="status === 'pending'">
            <div class="bg-yellow-500/10 p-4 rounded-full animate-pulse">
                <i class="fas fa-clock text-3xl text-yellow-500"></i>
            </div>
        </template>
        <template x-if="status === 'confirmed'">
            <div class="bg-green-500/10 p-4 rounded-full transition-all duration-700 scale-110">
                <i class="fas fa-check text-3xl text-green-500"></i>
            </div>
        </template>
        <template x-if="status === 'expired'">
            <div class="bg-red-500/10 p-4 rounded-full">
                <i class="fas fa-times text-3xl text-red-500"></i>
            </div>
        </template>
    </div>

    <!-- TÍTULO DINÂMICO -->
    <h2 class="text-3xl font-bold mb-2 italic uppercase tracking-tighter transition-all"
        x-text="status === 'pending' ? 'Aguardando Pagamento...' : status === 'confirmed' ? 'Agendado!' : 'Tempo Esgotado'">
    </h2>

    <p class="text-zinc-400 mb-6 text-sm">
        <template x-if="status === 'pending'">
            <span>Finalize o pagamento para confirmar seu horário.</span>
        </template>
        <template x-if="status === 'confirmed'">
            <span>Horário reservado com sucesso para <span class="text-white font-bold">{{ $appointment->user->name ?? $appointment->client_name ?? 'Cliente' }}</span>.</span>
        </template>
        <template x-if="status === 'expired'">
            <span>O tempo para pagamento <span class="text-red-400 font-bold">expirou</span> e o horário foi liberado.</span>
        </template>
    </p>

    <!-- COUNTDOWN (só aparece quando pending) -->
    <template x-if="status === 'pending'">
        <div class="mb-6">
            <div class="flex items-center justify-center gap-2 mb-2">
                <i class="fas fa-hourglass-half text-yellow-500 text-sm"></i>
                <span class="text-[11px] font-black uppercase tracking-widest text-zinc-500">Tempo restante para pagar</span>
            </div>
            <div class="text-4xl font-black font-mono tracking-widest"
                 :class="countdown <= 60 ? 'text-red-500 animate-pulse' : 'text-[#D4AF37]'"
                 x-text="formatCountdown(countdown)">
            </div>
            <div class="mt-3 h-1.5 bg-zinc-800 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-1000"
                     :class="countdown <= 60 ? 'bg-red-500' : 'bg-[#D4AF37]'"
                     :style="'width: ' + Math.round((countdown / totalSeconds) * 100) + '%'">
                </div>
            </div>
        </div>
    </template>

    <!-- RESUMO DO AGENDAMENTO -->
    <div class="bg-zinc-900/50 p-6 rounded-2xl mb-6 border border-zinc-800 text-left">
        <p class="text-[10px] text-zinc-500 uppercase font-bold tracking-widest mb-4">Resumo do Agendamento</p>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-zinc-400 text-sm">Serviço:</span>
                <span class="font-bold text-[#D4AF37]">{{ $appointment->service_label }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-zinc-400 text-sm">Data:</span>
                <span class="font-bold text-white">{{ date('d/m/Y', strtotime($appointment->date)) }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-zinc-400 text-sm">Horário:</span>
                <span class="font-bold text-white">{{ $appointment->time }}</span>
            </div>
            <div class="flex justify-between items-center border-t border-zinc-800 pt-3 mt-1">
                <span class="text-zinc-400 text-sm">Valor do PIX:</span>
                <span class="font-black text-[#D4AF37] text-lg">R$ {{ number_format($appointment->deposit_amount, 2, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- AVISO DE TOLERÂNCIA (só quando confirmado) -->
    <template x-if="status === 'confirmed'">
        <div class="bg-yellow-500/5 border border-yellow-500/30 rounded-2xl p-5 mb-6 text-left">
            <div class="flex items-start gap-3">
                <i class="fas fa-triangle-exclamation text-yellow-500 mt-0.5 text-base"></i>
                <div>
                    <p class="text-yellow-500 font-black uppercase text-[10px] tracking-widest mb-1">Atenção — Tolerância de Atraso</p>
                    <p class="text-zinc-400 text-xs leading-relaxed">
                        O seu horário possui uma tolerância de <span class="text-white font-bold">15 minutos</span> de atraso.
                        Após esse tempo, o horário poderá ser repassado e
                        <span class="text-red-400 font-bold">o sinal não será devolvido</span>.
                    </p>
                </div>
            </div>
        </div>
    </template>

    <!-- PIX (só quando pending) -->
    <template x-if="status === 'pending'">
        <div class="border-t border-zinc-800 pt-6 mb-6">
            <h4 class="text-[#D4AF37] font-bold text-xs uppercase mb-4 tracking-widest italic">Pague via PIX</h4>

            <div class="bg-white p-3 rounded-xl inline-block mb-5 shadow-lg">
                @if(isset($appointment->pix_qr_64))
                    <img src="data:image/png;base64, {{ $appointment->pix_qr_64 }}" class="w-40 h-40">
                @else
                    <div class="w-40 h-40 flex items-center justify-center text-black text-[10px] font-bold">Gerando QR Code...</div>
                @endif
            </div>

            <div x-data="{ copied: false }" class="relative">
                <input type="text" readonly value="{{ $appointment->pix_code ?? 'Erro ao gerar código' }}"
                       id="pixCode" class="w-full bg-black border border-zinc-800 text-zinc-500 text-[10px] p-4 pr-12 rounded-xl outline-none">
                <button @click="navigator.clipboard.writeText(document.getElementById('pixCode').value); copied = true; setTimeout(() => copied = false, 2000)"
                        type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-[#D4AF37]">
                    <i class="fas" :class="copied ? 'fa-check text-green-500' : 'fa-copy'"></i>
                </button>
            </div>
        </div>
    </template>

    <!-- SEÇÃO DE AVALIAÇÃO (só quando confirmed) -->
    <template x-if="status === 'confirmed'">
        <div class="mt-6 space-y-5">

            <!-- Formulário de avaliação -->
            <div x-show="!reviewSent && !couponCode" class="bg-zinc-900/60 border border-zinc-800 rounded-2xl p-5 text-left space-y-4">
                <p class="text-[10px] font-black uppercase text-zinc-500 tracking-widest">⭐ Como foi sua experiência?</p>

                <!-- Estrelas -->
                <div class="flex gap-2 justify-center">
                    <template x-for="star in [1,2,3,4,5]" :key="star">
                        <button type="button"
                                @click="reviewRating = star"
                                class="text-3xl transition-transform hover:scale-110 active:scale-95"
                                :class="star <= reviewRating ? 'text-[#D4AF37]' : 'text-zinc-700'">
                            ★
                        </button>
                    </template>
                </div>

                <!-- Comentário -->
                <textarea x-model="reviewComment"
                          placeholder="Deixe um comentário (opcional)..."
                          rows="3"
                          class="w-full bg-zinc-900 border border-zinc-800 rounded-xl p-3 text-white text-sm resize-none outline-none focus:border-[#D4AF37] placeholder-zinc-700"></textarea>

                <p x-show="reviewError" x-cloak class="text-red-400 text-[10px] font-bold">Selecione uma nota antes de enviar.</p>

                <button type="button"
                        @click="submitReview()"
                        class="w-full py-3 bg-zinc-800 hover:bg-zinc-700 text-white font-black uppercase rounded-xl transition-all text-[11px] tracking-widest">
                    Enviar Avaliação
                </button>
            </div>

            <!-- Pós-avaliação: convite Google + cupom -->
            <div x-show="reviewSent" x-cloak class="space-y-4">
                <div class="bg-green-500/10 border border-green-500/30 rounded-2xl p-4 text-center">
                    <p class="text-green-400 font-black text-sm uppercase tracking-widest">✅ Obrigado pela avaliação!</p>
                </div>

                @if($reviewCouponActive)
                <div x-show="!couponCode" class="bg-[#121212] border border-[#D4AF37]/30 rounded-2xl p-5 text-center space-y-3">
                    <p class="text-zinc-300 text-sm leading-relaxed">
                        Avalie também no <strong class="text-white">Google</strong> e ganhe <strong class="text-[#D4AF37]">{{ $reviewCouponPercent }}% de desconto</strong> no seu próximo corte 👇
                    </p>
                    <a href="https://www.google.com/search?q=nathan+do+corte+cascavel+ce#lrd=0x7b897004e3287eb:0x656e79c155a6c506,1,"
                       target="_blank"
                       @click="requestCoupon()"
                       class="inline-flex items-center gap-2 bg-white text-[#1A1C1E] font-black uppercase text-[11px] tracking-widest px-6 py-3 rounded-xl hover:bg-zinc-100 transition-all shadow-lg">
                        <img src="https://www.google.com/favicon.ico" class="w-4 h-4"> Avaliar no Google
                    </a>
                </div>

                <!-- Cupom gerado -->
                <div x-show="couponCode" x-cloak class="bg-[#D4AF37]/10 border border-[#D4AF37]/40 rounded-2xl p-5 text-center space-y-3">
                    <p class="text-[10px] font-black uppercase text-zinc-500 tracking-widest">Seu cupom de desconto</p>
                    <div class="flex items-center justify-center gap-3">
                        <span x-text="couponCode" class="text-[#D4AF37] font-black text-2xl tracking-widest font-mono"></span>
                        <button type="button"
                                @click="copyCoupon()"
                                class="px-3 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-white font-black text-[10px] uppercase rounded-lg transition-all">
                            <span x-text="couponCopied ? '✓ Copiado' : 'Copiar'"></span>
                        </button>
                    </div>
                    <p class="text-zinc-500 text-[10px] italic">Válido para {{ $reviewCouponPercent }}% de desconto no próximo agendamento</p>
                </div>
                @endif
            </div>

            <!-- Botão Voltar -->
            <a href="{{ url('/') }}"
               class="block w-full py-4 bg-[#D4AF37] text-black font-black uppercase rounded-xl hover:bg-[#f3ca4a] transition-all tracking-widest text-center">
                Voltar ao Início
            </a>
        </div>
    </template>

    <template x-if="status === 'expired'">
        <a href="{{ route('appointments.create', ['service' => 'geral']) }}"
           class="block w-full py-4 bg-zinc-800 border border-zinc-700 text-white font-black uppercase rounded-xl hover:bg-zinc-700 transition-all tracking-widest">
            Tentar Novamente
        </a>
    </template>
</div>

<script>
function pagamento() {
    return {
        status: '{{ $appointment->status }}',
        totalSeconds: 10 * 60,
        countdown: 0,
        pollInterval: null,
        countdownInterval: null,

        reviewRating: 0,
        reviewComment: '',
        reviewSent: false,
        reviewError: false,
        couponCode: null,
        couponCopied: false,

        init() {
            if (this.status !== 'pending') return;

            // Calcula tempo restante baseado em created_at
            const createdAt  = new Date('{{ $appointment->created_at->toISOString() }}');
            const expiresAt  = new Date(createdAt.getTime() + this.totalSeconds * 1000);
            const now        = new Date();
            this.countdown   = Math.max(0, Math.round((expiresAt - now) / 1000));

            // Se já expirou no lado do cliente, mostra expirado imediatamente
            if (this.countdown <= 0) {
                this.status = 'expired';
                return;
            }

            // Countdown regressivo
            this.countdownInterval = setInterval(() => {
                this.countdown--;
                if (this.countdown <= 0) {
                    this.countdown = 0;
                    clearInterval(this.countdownInterval);
                }
            }, 1000);

            // Polling a cada 3s para detectar pagamento confirmado ou expiração
            this.pollInterval = setInterval(() => {
                fetch('/agendar/status/{{ $appointment->id }}')
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'confirmed') {
                            this.status = 'confirmed';
                            this.stopAll();
                        } else if (data.status === 'expired' || data.status === 'canceled') {
                            this.status = 'expired';
                            this.stopAll();
                        }
                    })
                    .catch(() => {});
            }, 3000);
        },

        stopAll() {
            clearInterval(this.pollInterval);
            clearInterval(this.countdownInterval);
        },

        formatCountdown(seconds) {
            const m = String(Math.floor(seconds / 60)).padStart(2, '0');
            const s = String(seconds % 60).padStart(2, '0');
            return m + ':' + s;
        },

        submitReview() {
            if (this.reviewRating === 0) { this.reviewError = true; return; }
            this.reviewError = false;

            fetch('{{ route('reviews.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    appointment_id: {{ $appointment->id }},
                    rating: this.reviewRating,
                    comment: this.reviewComment,
                }),
            })
            .then(r => r.json())
            .then(() => { this.reviewSent = true; })
            .catch(() => { this.reviewSent = true; }); // exibe pós-avaliação mesmo se der erro
        },

        requestCoupon() {
            fetch('{{ route('reviews.generateCoupon') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ appointment_id: {{ $appointment->id }} }),
            })
            .then(r => r.json())
            .then(data => { if (data.code) this.couponCode = data.code; })
            .catch(() => {});
        },

        copyCoupon() {
            if (this.couponCode) {
                navigator.clipboard.writeText(this.couponCode);
                this.couponCopied = true;
                setTimeout(() => { this.couponCopied = false; }, 2000);
            }
        },
    };
}
</script>

</body>
</html>
