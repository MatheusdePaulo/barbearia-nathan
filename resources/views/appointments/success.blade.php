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

<div x-data="{ status: '{{ $appointment->status }}' }"
     x-init="setInterval(() => {
        if (status === 'pending') {
            fetch('/agendar/status/{{ $appointment->id }}')
                .then(response => {
                    if (!response.ok) throw new Error('Erro na rede');
                    return response.json();
                })
                .then(data => {
                    console.log('Status atual:', data.status);
                    if(data.status === 'confirmed') {
                        status = 'confirmed';
                    }
                })
                .catch(error => console.error('Erro no fetch:', error));
        }
    }, 3000)"
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
    </div>

    <!-- TÍTULO DINÂMICO -->
    <h2 class="text-3xl font-bold mb-2 italic uppercase tracking-tighter transition-all"
        x-text="status === 'pending' ? 'Aguardando Pagamento...' : 'Agendado!'">
    </h2>

    <p class="text-zinc-400 mb-8 text-sm">
        <template x-if="status === 'pending'">
            <span>Finalize o pagamento do sinal para confirmar.</span>
        </template>
        <template x-if="status === 'confirmed'">
            <span>Horário reservado com sucesso para <span class="text-white font-bold">{{ auth()->user()->name }}</span>.</span>
        </template>
    </p>

    <!-- RESUMO DO AGENDAMENTO -->
    <div class="bg-zinc-900/50 p-6 rounded-2xl mb-8 border border-zinc-800 text-left">
        <p class="text-[10px] text-zinc-500 uppercase font-bold tracking-widest mb-4">Resumo do Agendamento</p>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-zinc-400 text-sm">Serviço:</span>
                <span class="font-bold text-[#D4AF37]">{{ $appointment->service->name }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-zinc-400 text-sm">Data:</span>
                <span class="font-bold text-white">{{ date('d/m/Y', strtotime($appointment->date)) }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-zinc-400 text-sm">Horário:</span>
                <span class="font-bold text-white">{{ $appointment->time }}</span>
            </div>
        </div>
    </div>

    <!-- SEÇÃO DO PIX DINÂMICA -->
    <div x-show="status === 'pending'" class="border-t border-zinc-800 pt-8 mb-8 transition-all">
        <h4 class="text-[#D4AF37] font-bold text-xs uppercase mb-4 tracking-widest italic">Pague a Taxa de Reserva (R$ 5,00)</h4>

        <!-- QR CODE REAL VINDO DO MERCADO PAGO -->
        <div class="bg-white p-3 rounded-xl inline-block mb-6 shadow-lg">
            @if(isset($appointment->pix_qr_64))
                <img src="data:image/png;base64, {{ $appointment->pix_qr_64 }}" class="w-40 h-40">
            @else
                <div class="w-40 h-40 flex items-center justify-center text-black text-[10px] font-bold">Gerando QR Code...</div>
            @endif
        </div>

        <!-- COPIA E COLA REAL -->
        <div x-data="{ copied: false }" class="relative">
            <input type="text" readonly value="{{ $appointment->pix_code ?? 'Erro ao gerar código' }}"
                   id="pixCode" class="w-full bg-black border border-zinc-800 text-zinc-500 text-[10px] p-4 pr-12 rounded-xl outline-none">
            <button @click="navigator.clipboard.writeText(document.getElementById('pixCode').value); copied = true; setTimeout(() => copied = false, 2000)"
                    type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-[#D4AF37]">
                <i class="fas" :class="copied ? 'fa-check text-green-500' : 'fa-copy'"></i>
            </button>
        </div>
    </div>

    <!-- BOTÃO FINAL -->
    <a href="{{ url('/') }}"
       class="block w-full py-4 bg-[#D4AF37] text-black font-black uppercase rounded-xl hover:bg-[#f3ca4a] transition-all tracking-widest">
        Voltar ao Início
    </a>
</div>

</body>
</html>
