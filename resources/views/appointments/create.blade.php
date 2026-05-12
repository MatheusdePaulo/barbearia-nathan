<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barber Nathan | Agendar Horário</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body { background-color: #050505; color: white; font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: #121212; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #D4AF37; border-radius: 10px; }
    </style>
</head>
<body class="flex flex-col lg:flex-row min-h-screen overflow-x-hidden" x-data="{ mobileMenu: false }">

<!-- HEADER MÓVEL -->
<header class="lg:hidden bg-[#121212] border-b border-zinc-800 p-5 flex justify-between items-center sticky top-0 z-40">
    <img src="{{ asset('images/logotipo_nathan.png') }}" alt="Barber Nathan" class="h-12 w-auto">
    <button @click="mobileMenu = true" class="text-[#D4AF37] p-2">
        <i class="fas fa-bars text-3xl"></i>
    </button>
</header>

<!-- SIDEBAR DESKTOP -->
<aside class="w-[300px] bg-[#121212] border-r border-zinc-800 p-8 hidden lg:block flex-shrink-0 sticky top-0 h-screen text-center">
    <div class="mb-10">
        <img src="{{ asset('images/logotipo_nathan.png') }}" alt="Barber Nathan" class="w-40 h-auto mx-auto">
    </div>
    <nav class="space-y-4">
        <a href="{{ url('/') }}" class="block px-4 py-3 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-xl transition-all italic font-bold text-sm">← Voltar ao Início</a>
        <div class="pt-8">
            <button class="w-full py-4 bg-zinc-800 text-white font-black uppercase rounded-xl border border-zinc-700 hover:bg-zinc-700 transition-all text-[11px] tracking-[0.2em]">
                CORTES ANTIGOS
            </button>
        </div>
    </nav>
</aside>

<!-- SIDEBAR MÓVEL -->
<div x-show="mobileMenu" x-cloak class="fixed inset-0 z-50 lg:hidden">
    <div @click="mobileMenu = false" class="fixed inset-0 bg-black/90 backdrop-blur-md"></div>
    <nav class="fixed top-0 right-0 bottom-0 w-[300px] bg-[#121212] p-10 shadow-2xl flex flex-col items-center">
        <button @click="mobileMenu = false" class="self-end text-white p-2 mb-10"><i class="fas fa-times text-3xl"></i></button>
        <img src="{{ asset('images/logotipo_nathan.png') }}" alt="Barber Nathan" class="w-40 h-auto mb-10">
        <a href="{{ url('/') }}" class="w-full text-center px-4 py-4 text-zinc-400 border border-zinc-800 rounded-2xl font-black uppercase text-xs">Voltar ao Início</a>
    </nav>
</div>

<!-- CONTEÚDO PRINCIPAL -->
<main class="flex-1 p-6 md:p-12 lg:p-16 flex justify-start">
    <div class="w-full max-w-6xl">

        <form action="{{ route('appointments.store') }}" method="POST"
              x-data="{
                      paymentType: 'signal',
                      selectedPrice: {{ (isset($selectedService) && is_object($selectedService)) ? $selectedService->price : 0 }}
                  }">
            @csrf

            {{-- BLOCO DE FEEDBACK DE ERRO --}}
            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500 text-red-500 p-5 rounded-2xl mb-8 font-black uppercase text-[10px] tracking-widest italic flex items-center gap-4 animate-pulse">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-16 gap-y-12 items-start">

                <!-- CABEÇALHOS -->
                <header class="lg:mb-4">
                    <span class="text-[#D4AF37] font-black uppercase tracking-[0.3em] text-[10px]">Confirmar Agendamento</span>
                    <h2 class="text-4xl font-black italic mt-2 uppercase tracking-tight leading-none text-white">Agendamento Geral</h2>
                </header>

                <div class="bg-[#121212] border border-zinc-800 p-6 rounded-3xl border-l-[6px] border-l-[#D4AF37] shadow-xl">
                    <p class="text-zinc-500 text-[10px] uppercase font-black tracking-widest mb-1 opacity-70">Cliente Logado</p>
                    <h4 class="text-xl font-black text-white uppercase">{{ auth()->user()->name }}</h4>
                    <p class="text-zinc-400 text-xs mt-1 italic font-medium">WhatsApp: {{ auth()->user()->whatsapp ?? auth()->user()->phone ?? 'Não cadastrado' }}</p>
                </div>

                <!-- 1. ESCOLHA O SERVIÇO -->
                <div class="space-y-6">
                    <label class="block text-zinc-500 text-[11px] font-black uppercase tracking-[0.2em] italic opacity-60">1. Escolha o Serviço</label>
                    <div class="space-y-3.5 pr-3 max-h-[550px] overflow-y-auto custom-scroll">
                        @foreach($services as $s)
                            <label class="relative flex items-center justify-between bg-[#121212] border border-zinc-800/60 p-5 rounded-2xl cursor-pointer group transition-all duration-300 hover:border-[#D4AF37]/50">
                                <input type="radio" name="service_id" value="{{ $s->id }}" class="hidden peer" required
                                       @click="selectedPrice = {{ $s->price }}"
                                    {{ (isset($selectedService) && is_object($selectedService) && $selectedService->id == $s->id) ? 'checked' : '' }}>

                                <div class="flex items-center gap-5">
                                    <div class="relative w-16 h-16 shrink-0 bg-white rounded-xl overflow-hidden border border-zinc-700 flex items-center justify-center p-1.5">
                                        <img src="{{ asset('images/' . $s->image) }}" alt="{{ $s->name }}" class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110">
                                    </div>
                                    <div>
                                        <p class="text-[#D4AF37] font-black uppercase text-base tracking-tight group-hover:text-white transition-colors">{{ $s->name }}</p>
                                        <p class="text-zinc-500 text-xs font-bold mt-1">R$ {{ number_format($s->price, 2, ',', '.') }} | <span class="italic">{{ $s->duration ?? '30' }}min</span></p>
                                    </div>
                                </div>
                                <div class="w-6 h-6 border-2 border-zinc-800 rounded-full flex items-center justify-center peer-checked:border-[#D4AF37] transition-all duration-300 shadow-inner">
                                    <div class="w-3 h-3 bg-[#D4AF37] rounded-full opacity-0 scale-50 peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-300"></div>
                                </div>
                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-[#D4AF37] rounded-2xl pointer-events-none transition-all duration-300"></div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 2, 3 e 4. DADOS DE AGENDAMENTO -->
                <div class="space-y-10">
                    <div>
                        <label class="block text-zinc-500 text-[11px] font-black uppercase tracking-[0.2em] italic opacity-60 mb-4">2. Escolha a Data</label>
                        <input type="date" name="date" required
                               @change="window.location.href = '?date=' + $el.value"
                               class="w-full bg-[#121212] border border-zinc-800 rounded-2xl p-4.5 focus:border-[#D4AF37] outline-none transition-all text-base font-black text-white uppercase tracking-widest shadow-lg"
                               value="{{ $selectedDate }}"
                               min="{{ date('Y-m-d') }}">
                    </div>

                    <div>
                        <label class="block text-zinc-500 text-[11px] font-black uppercase tracking-[0.2em] italic opacity-60 mb-4">3. Selecione o Horário</label>
                        <div class="grid grid-cols-4 gap-3">
                            @foreach($slots as $s)
                                <label class="{{ $s['available'] ? 'cursor-pointer group' : 'cursor-not-allowed opacity-20' }}">
                                    <input type="radio" name="time" value="{{ $s['time'] }}" class="hidden peer" {{ $s['available'] ? 'required' : 'disabled' }}>
                                    <div class="bg-[#121212] border border-zinc-800/80 py-4 rounded-xl text-center transition-all peer-checked:bg-[#D4AF37] peer-checked:text-black peer-checked:border-[#D4AF37] text-white font-black text-[11px] shadow-sm">{{ $s['time'] }}</div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-zinc-500 text-[11px] font-black uppercase tracking-[0.2em] italic opacity-60 mb-4">4. Opção de Pagamento Pix</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_type" value="signal" x-model="paymentType" class="hidden peer">
                                <div class="bg-[#121212] border border-zinc-800 p-5 rounded-2xl transition-all peer-checked:border-[#D4AF37] peer-checked:bg-[#D4AF37]/5 h-full flex flex-col justify-center shadow-md">
                                    <p class="text-[10px] font-black uppercase text-zinc-600 peer-checked:text-[#D4AF37] mb-1">Pagar Sinal</p>
                                    <p class="text-white font-black text-xl tracking-tight">R$ 5,00</p>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_type" value="full" x-model="paymentType" class="hidden peer">
                                <div class="bg-[#121212] border border-zinc-800 p-5 rounded-2xl transition-all peer-checked:border-[#D4AF37] peer-checked:bg-[#D4AF37]/5 h-full flex flex-col justify-center shadow-md">
                                    <p class="text-[10px] font-black uppercase text-zinc-600 peer-checked:text-[#D4AF37] mb-1">Pagar Inteiro</p>
                                    <p class="text-white font-black text-xl tracking-tight">R$ <span x-text="parseFloat(selectedPrice).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span></p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-5 bg-[#D4AF37] text-black font-black uppercase rounded-2xl hover:bg-[#f3ca4a] hover:-translate-y-0.5 transition-all tracking-[0.25em] text-xs shadow-2xl shadow-[#D4AF37]/10">
                        Confirmar e Gerar Pix
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>
</body>
</html>
