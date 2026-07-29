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
        input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; }
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: #121212; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #D4AF37; border-radius: 10px; }
    </style>
</head>
<body class="flex flex-col lg:flex-row min-h-screen overflow-x-hidden"
      x-data="agendamento()"
      x-init="init()">

<header class="lg:hidden bg-[#121212] border-b border-zinc-800 p-5 flex justify-between items-center sticky top-0 z-40">
    <img src="{{ asset('images/logotipo_nathan.png') }}" alt="Barber Nathan" class="h-12 w-auto">
    <button @click="mobileMenu = true" class="text-[#D4AF37] p-2">
        <i class="fas fa-bars text-3xl"></i>
    </button>
</header>

<aside class="w-[300px] bg-[#121212] border-r border-zinc-800 p-8 hidden lg:block flex-shrink-0 sticky top-0 h-screen text-center">
    <div class="mb-10">
        <img src="{{ asset('images/logotipo_nathan.png') }}" alt="Barber Nathan" class="w-40 h-auto mx-auto">
    </div>
    <nav class="space-y-4">
        <a href="{{ url('/') }}" class="block px-4 py-3 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-xl transition-all italic font-bold text-sm">← Voltar ao Início</a>
    </nav>
</aside>

<div x-show="mobileMenu" x-cloak class="fixed inset-0 z-50 lg:hidden">
    <div @click="mobileMenu = false" class="fixed inset-0 bg-black/90 backdrop-blur-md"></div>
    <nav class="fixed top-0 right-0 bottom-0 w-[300px] bg-[#121212] p-10 shadow-2xl flex flex-col items-center">
        <button @click="mobileMenu = false" class="self-end text-white p-2 mb-10"><i class="fas fa-times text-3xl"></i></button>
        <img src="{{ asset('images/logotipo_nathan.png') }}" alt="Barber Nathan" class="w-40 h-auto mb-10">
        <a href="{{ url('/') }}" class="w-full text-center px-4 py-4 text-zinc-400 border border-zinc-800 rounded-2xl font-black uppercase text-xs">Voltar ao Início</a>
    </nav>
</div>

<main class="flex-1 p-6 md:p-12 lg:p-16 flex justify-start">
    <div class="w-full max-w-6xl">

        <form action="{{ route('appointments.store') }}" method="POST" id="formAgendamento">
            @csrf

            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500 text-red-500 p-5 rounded-2xl mb-8 font-black uppercase text-[10px] tracking-widest italic flex items-center gap-4 animate-pulse">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <header class="mb-10">
                <span class="text-[#D4AF37] font-black uppercase tracking-[0.3em] text-[10px]">Confirmar Agendamento</span>
                <h2 class="text-4xl font-black italic mt-2 uppercase tracking-tight leading-none text-white">Agendamento Geral</h2>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-16 gap-y-12 items-start">

                <!-- ===== COLUNA ESQUERDA: DATA + SERVIÇOS ===== -->
                <div class="space-y-10">

                    <!-- 1. ESCOLHA A DATA -->
                    <div>
                        <label class="block text-zinc-500 text-[11px] font-black uppercase tracking-[0.2em] italic opacity-60 mb-4">1. Escolha a Data</label>
                        <input type="date" name="date" required
                               @change="reloadWithServices($el.value)"
                               class="w-full bg-[#121212] border border-zinc-800 rounded-2xl p-4.5 focus:border-[#D4AF37] outline-none transition-all text-base font-black text-white uppercase tracking-widest shadow-lg"
                               value="{{ $selectedDate }}"
                               min="{{ date('Y-m-d') }}">
                    </div>

                    <!-- 2. ESCOLHA OS SERVIÇOS (múltipla seleção) -->
                    <div class="space-y-4">
                        <div class="flex items-end justify-between">
                            <label class="block text-zinc-500 text-[11px] font-black uppercase tracking-[0.2em] italic opacity-60">2. Escolha os Serviços</label>
                            <span class="text-[10px] font-black italic transition-colors"
                                  :class="selectedServices.length >= 3 ? 'text-[#D4AF37]' : 'text-zinc-600'"
                                  x-text="selectedServices.length + '/3 selecionados'"></span>
                        </div>

                        <div class="space-y-3.5 pr-3 max-h-[550px] overflow-y-auto custom-scroll">
                            @foreach($services as $s)
                                <label class="relative flex items-center justify-between bg-[#121212] border border-zinc-800/60 p-5 rounded-2xl transition-all duration-300"
                                       :class="{
                                           'border-[#D4AF37]/80 bg-[#D4AF37]/5': isSelected({{ $s->id }}),
                                           'cursor-pointer group hover:border-[#D4AF37]/50': isSelected({{ $s->id }}) || selectedServices.length < 3,
                                           'opacity-40 cursor-not-allowed': !isSelected({{ $s->id }}) && selectedServices.length >= 3
                                       }">

                                    <input type="checkbox"
                                           name="service_ids[]"
                                           value="{{ $s->id }}"
                                           class="hidden"
                                           :checked="isSelected({{ $s->id }})"
                                           @change="toggleService({{ $s->id }}, {{ (float)$s->active_price }}, {{ (int)$s->duration }}, '{{ addslashes($s->name) }}')">

                                    <div class="flex items-center gap-5">
                                        <div class="relative w-16 h-16 shrink-0 bg-white rounded-xl overflow-hidden border border-zinc-700 flex items-center justify-center p-1.5">
                                            <img src="{{ asset('images/' . $s->image) }}" alt="{{ $s->name }}" class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110" loading="lazy">
                                        </div>
                                        <div>
                                            <p class="font-black uppercase text-base tracking-tight transition-colors"
                                               :class="isSelected({{ $s->id }}) ? 'text-white' : 'text-[#D4AF37] group-hover:text-white'">{{ $s->name }}</p>

                                            @if($s->is_promo)
                                                <p class="text-zinc-500 text-xs font-bold mt-1">
                                                    <span class="line-through opacity-50">R$ {{ number_format($s->price, 2, ',', '.') }}</span>
                                                    <span class="text-[#D4AF37]">R$ {{ number_format($s->promo_price, 2, ',', '.') }}</span>
                                                    | <span class="italic">{{ $s->duration }}min</span>
                                                </p>
                                            @else
                                                <p class="text-zinc-500 text-xs font-bold mt-1">R$ {{ number_format($s->price, 2, ',', '.') }} | <span class="italic">{{ $s->duration }}min</span></p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Checkbox visual -->
                                    <div class="w-6 h-6 border-2 rounded-md flex items-center justify-center transition-all duration-300 shrink-0"
                                         :class="isSelected({{ $s->id }}) ? 'border-[#D4AF37] bg-[#D4AF37]' : 'border-zinc-700'">
                                        <i class="fas fa-check text-black text-[10px] transition-opacity"
                                           :class="isSelected({{ $s->id }}) ? 'opacity-100' : 'opacity-0'"></i>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <!-- Resumo dos serviços selecionados -->
                        <div x-show="selectedServices.length > 0" x-cloak
                             class="bg-[#121212] border border-[#D4AF37]/30 rounded-2xl p-4 space-y-2">
                            <p class="text-[10px] font-black uppercase text-zinc-500 tracking-widest mb-3">Selecionados</p>
                            <template x-for="svc in selectedServices" :key="svc.id">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-zinc-300 font-bold" x-text="svc.name"></span>
                                    <span class="text-zinc-500 font-mono" x-text="svc.duration + 'min'"></span>
                                </div>
                            </template>
                            <div class="border-t border-zinc-800 pt-2 mt-2 flex justify-between items-center">
                                <span class="text-[10px] font-black uppercase text-zinc-500">Total</span>
                                <div class="text-right">
                                    <span class="text-[#D4AF37] font-black font-mono text-sm" x-text="'R$ ' + totalPrice.toLocaleString('pt-BR', {minimumFractionDigits:2})"></span>
                                    <span class="text-zinc-600 text-[10px] font-bold ml-2" x-text="'| ' + totalDuration + 'min'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Aviso quando nenhum serviço selecionado -->
                        <div x-show="selectedServices.length === 0" x-cloak
                             class="bg-zinc-900/50 border border-zinc-800 rounded-2xl p-4 text-center">
                            <p class="text-zinc-600 text-[11px] font-bold italic">Selecione pelo menos um serviço para ver os horários disponíveis</p>
                        </div>

                    </div>

                </div>

                <!-- ===== COLUNA DIREITA: CLIENTE + HORÁRIO + PAGAMENTO ===== -->
                <div class="space-y-10">

                    @auth
                    <!-- CLIENTE LOGADO -->
                    <div class="bg-[#121212] border border-zinc-800 p-6 rounded-3xl border-l-[6px] border-l-[#D4AF37] shadow-xl">
                        <p class="text-zinc-500 text-[10px] uppercase font-black tracking-widest mb-1 opacity-70">Cliente Logado</p>
                        <h4 class="text-xl font-black text-white uppercase">{{ auth()->user()->name }}</h4>
                        <p class="text-zinc-400 text-xs mt-1 italic font-medium">WhatsApp: {{ auth()->user()->whatsapp ?? 'Não cadastrado' }}</p>
                    </div>
                    @endauth

                    @guest
                    <!-- AVISO PARA GUEST -->
                    <div class="bg-[#121212] border border-zinc-800/60 p-5 rounded-3xl border-l-[6px] border-l-zinc-600 shadow-xl">
                        <p class="text-zinc-500 text-[10px] uppercase font-black tracking-widest mb-1 opacity-70">Agendando como</p>
                        <h4 class="text-base font-black text-zinc-300 uppercase">Visitante</h4>
                        <p class="text-zinc-600 text-[10px] mt-1 italic font-medium">Seus dados serão pedidos ao confirmar</p>
                    </div>
                    @endguest

                    <!-- 3. SELECIONE O HORÁRIO -->
                    <div>
                        <label class="block text-zinc-500 text-[11px] font-black uppercase tracking-[0.2em] italic opacity-60 mb-2">3. Selecione o Horário</label>

                        @if($isClosed)
                            <div class="bg-zinc-900 border border-zinc-800 p-8 rounded-2xl text-center">
                                <i class="fas fa-store-slash text-2xl text-zinc-700 mb-3"></i>
                                <p class="text-zinc-500 font-black text-xs uppercase italic">Sem atendimento neste dia</p>
                            </div>
                        @else
                            @if($selectedIds)
                                <p class="text-[10px] text-zinc-600 font-bold italic mb-4">
                                    Bloqueando {{ $slotsNeeded }} slot{{ $slotsNeeded > 1 ? 's' : '' }} consecutivo{{ $slotsNeeded > 1 ? 's' : '' }} ({{ $totalDuration }}min total)
                                </p>
                            @endif

                            <div class="grid grid-cols-4 gap-3">
                                @foreach($slots as $s)
                                    <label class="{{ $s['available'] ? 'cursor-pointer group' : 'cursor-not-allowed opacity-20' }}">
                                        <input type="radio" name="time" value="{{ $s['time'] }}" class="hidden peer" {{ $s['available'] ? 'required' : 'disabled' }}>
                                        <div class="bg-[#121212] border border-zinc-800/80 py-4 rounded-xl text-center transition-all peer-checked:bg-[#D4AF37] peer-checked:text-black peer-checked:border-[#D4AF37] text-white font-black text-[11px] shadow-sm">{{ $s['time'] }}</div>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- 4. OPÇÃO DE PAGAMENTO PIX -->
                    <div>
                        <label class="block text-zinc-500 text-[11px] font-black uppercase tracking-[0.2em] italic opacity-60 mb-4">4. Opção de Pagamento Pix</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_type" value="signal" x-model="paymentType" class="hidden peer" checked>
                                <div class="bg-[#121212] border border-zinc-800 p-5 rounded-2xl transition-all peer-checked:border-[#D4AF37] peer-checked:bg-[#D4AF37]/5 h-full flex flex-col justify-center shadow-md">
                                    <p class="text-[10px] font-black uppercase text-zinc-600 mb-1">Pagar Sinal</p>
                                    <p class="font-black text-xl tracking-tight transition-all duration-300"
                                       :class="couponAnimating ? 'text-green-400 scale-110' : 'text-white'">
                                        R$ <span x-text="signalPrice.toLocaleString('pt-BR', {minimumFractionDigits:2})"></span>
                                    </p>
                                    <p x-show="couponValid" x-cloak
                                       class="text-zinc-600 text-[9px] mt-0.5 line-through">R$ 5,00</p>
                                    <p class="text-zinc-600 text-[9px] mt-1 italic">Desconta no total</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_type" value="full" x-model="paymentType" class="hidden peer">
                                <div class="bg-[#121212] border border-zinc-800 p-5 rounded-2xl transition-all peer-checked:border-[#D4AF37] peer-checked:bg-[#D4AF37]/5 h-full flex flex-col justify-center shadow-md">
                                    <p class="text-[10px] font-black uppercase text-zinc-600 mb-1">Pagar Inteiro</p>
                                    <p class="font-black text-xl tracking-tight font-mono transition-all duration-300"
                                       :class="couponAnimating ? 'text-green-400 scale-110' : 'text-white'">
                                        R$ <span x-text="discountedPrice.toLocaleString('pt-BR', {minimumFractionDigits:2})"></span>
                                    </p>
                                    <p x-show="couponValid" x-cloak
                                       class="text-zinc-600 text-[9px] mt-0.5 line-through font-mono"
                                       x-text="'R$ ' + totalPrice.toLocaleString('pt-BR', {minimumFractionDigits:2})"></p>
                                    <p class="text-zinc-600 text-[9px] mt-1 italic" x-text="selectedServices.length > 1 ? selectedServices.length + ' serviços' : 'valor total'"></p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- CUPOM DE DESCONTO -->
                    <div class="space-y-2">
                        <label class="block text-zinc-500 text-[11px] font-black uppercase tracking-[0.2em] italic opacity-60 mb-1">Cupom de Desconto <span class="text-zinc-700">(opcional)</span></label>
                        <div class="flex gap-2">
                            <input type="text"
                                   x-model="couponCode"
                                   @keydown.enter.prevent="aplicarCupom()"
                                   :disabled="couponValid"
                                   placeholder="EX: AVA-X7K2P"
                                   class="flex-1 bg-[#121212] border border-zinc-800 rounded-xl py-3 px-4 text-white text-sm uppercase tracking-widest outline-none focus:border-[#D4AF37] placeholder-zinc-700 font-mono disabled:opacity-60">
                            <button type="button"
                                    @click="couponValid ? removerCupom() : aplicarCupom()"
                                    :disabled="!couponCode.trim() && !couponValid"
                                    :class="couponValid ? 'bg-red-900/40 hover:bg-red-900/60 text-red-400' : 'bg-zinc-800 hover:bg-zinc-700 text-white'"
                                    class="px-4 py-3 disabled:opacity-40 font-black uppercase text-[10px] rounded-xl transition-all tracking-widest whitespace-nowrap">
                                <span x-text="couponValid ? 'Remover' : 'Aplicar'"></span>
                            </button>
                        </div>
                        <p x-show="couponMessage" x-cloak
                           :class="couponValid ? 'text-green-400' : 'text-red-400'"
                           class="text-[10px] font-bold ml-1" x-text="couponMessage"></p>
                        <input type="hidden" name="coupon_code" :value="couponValid ? couponCode.trim().toUpperCase() : ''">
                    </div>

                    <!-- BOTÃO CONFIRMAR -->
                    @auth
                    <button type="submit"
                            :disabled="selectedServices.length === 0"
                            :class="selectedServices.length === 0 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-[#f3ca4a] hover:-translate-y-0.5'"
                            class="w-full py-5 bg-[#D4AF37] text-black font-black uppercase rounded-2xl transition-all tracking-[0.25em] text-xs shadow-2xl shadow-[#D4AF37]/10">
                        Confirmar e Gerar Pix
                    </button>
                    @else
                    <button type="button"
                            :disabled="selectedServices.length === 0"
                            :class="selectedServices.length === 0 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-[#f3ca4a] hover:-translate-y-0.5'"
                            @click="selectedServices.length > 0 && (showGuestModal = true)"
                            class="w-full py-5 bg-[#D4AF37] text-black font-black uppercase rounded-2xl transition-all tracking-[0.25em] text-xs shadow-2xl shadow-[#D4AF37]/10">
                        Confirmar e Gerar Pix
                    </button>
                    @endauth

                    <!-- INFORMAÇÕES DE PAGAMENTO -->
                    <div class="bg-[#121212] border border-zinc-800 rounded-2xl p-5 shadow-inner">
                        <div class="flex items-start gap-4">
                            <i class="fas fa-info-circle text-[#D4AF37] mt-1 text-lg"></i>
                            <div class="space-y-2 text-left">
                                <h4 class="text-white font-black uppercase text-[10px] tracking-[0.2em] italic">Informações de Pagamento</h4>
                                <ul class="text-zinc-400 text-[11px] leading-relaxed font-medium space-y-1.5">
                                    <li class="flex gap-2"><span>•</span> <span>O valor do <strong>sinal</strong> é obrigatório para confirmar o horário e será <strong>descontado</strong> no valor total no estabelecimento.</span></li>
                                    <li class="flex gap-2 text-red-400/80"><span>•</span> <span>Em caso de <strong>falta</strong>, o valor do sinal não será devolvido.</span></li>
                                    <li class="flex gap-2"><span>•</span> <span>Pagamento <strong>integral</strong> com devolução total mediante aviso prévio.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</main>

@guest
{{-- MODAL DE IDENTIFICAÇÃO PARA VISITANTES --}}
<div x-show="showGuestModal"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     @keydown.escape.window="showGuestModal = false">

    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="showGuestModal = false"></div>

    <div class="relative bg-[#121212] border border-zinc-800 rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-[#D4AF37]"></div>

        <div class="p-8 space-y-6">
            <div class="text-center">
                <p class="text-[9px] font-black uppercase text-[#D4AF37] tracking-widest mb-1">Quase lá!</p>
                <h3 class="text-xl font-black italic text-white uppercase tracking-tight">Seus Dados</h3>
                <p class="text-zinc-600 text-[10px] font-bold mt-1">Para identificar seu agendamento</p>
            </div>

            <div class="space-y-4">
                {{-- NOME --}}
                <div class="space-y-1">
                    <label class="text-[9px] font-black uppercase text-[#D4AF37] ml-2 italic tracking-widest">Nome *</label>
                    <input type="text"
                           x-model="guestName"
                           placeholder="Seu nome completo"
                           class="block w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl py-3.5 px-5 text-white text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37]/20 outline-none transition-all placeholder-zinc-700">
                    <p x-show="guestNameError" x-cloak class="text-red-400 text-[10px] font-bold ml-2 mt-1">Informe seu nome</p>
                </div>

                {{-- WHATSAPP --}}
                <div class="space-y-1">
                    <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">WhatsApp <span class="text-zinc-700">(opcional)</span></label>
                    <input type="text"
                           x-model="guestWhatsapp"
                           x-mask="(99) 99999-9999"
                           placeholder="(85) 90000-0000"
                           class="block w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl py-3.5 px-5 text-white text-sm focus:border-[#D4AF37] focus:ring-0 outline-none transition-all placeholder-zinc-700">
                </div>

                {{-- NASCIMENTO --}}
                <div class="space-y-1">
                    <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">Data de Nascimento <span class="text-zinc-700">(opcional)</span></label>
                    <input type="date"
                           x-model="guestBirthday"
                           style="color-scheme: dark;"
                           class="block w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl py-3.5 px-5 text-white text-sm focus:border-[#D4AF37] focus:ring-0 outline-none transition-all">
                </div>

                {{-- LEMBRAR DADOS --}}
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <input type="checkbox"
                           x-model="rememberGuest"
                           class="rounded border-zinc-700 bg-zinc-900 text-[#D4AF37] focus:ring-0 shadow-none w-4 h-4">
                    <span class="text-[10px] font-black text-zinc-500 uppercase italic tracking-widest">Lembrar meus dados</span>
                </label>
            </div>

            <button type="button"
                    @click="submitGuestForm()"
                    class="w-full py-4 bg-[#D4AF37] hover:bg-[#f3ca4a] text-black font-black uppercase rounded-2xl transition-all tracking-[0.25em] text-[11px] shadow-2xl shadow-[#D4AF37]/10 active:scale-[0.98]">
                Confirmar e Gerar Pix
            </button>
        </div>
    </div>
</div>
@endguest

<script>
function agendamento() {
    return {
        mobileMenu: false,
        paymentType: 'signal',
        selectedServices: [],
        maxServices: 3,
        showGuestModal: false,
        guestName: '',
        guestWhatsapp: '',
        guestBirthday: '',
        rememberGuest: false,
        guestNameError: false,

        couponCode: '',
        couponValid: false,
        couponDiscount: 0,
        couponMessage: '',
        couponAnimating: false,

        preselectedIds: @json($selectedIds),

        init() {
            @foreach($services as $s)
            if (this.preselectedIds.includes({{ $s->id }})) {
                this.selectedServices.push({
                    id: {{ $s->id }},
                    price: {{ (float)$s->active_price }},
                    duration: {{ (int)$s->duration }},
                    name: '{{ addslashes($s->name) }}'
                });
            }
            @endforeach

            // Recupera dados salvos no localStorage
            try {
                const saved = JSON.parse(localStorage.getItem('barber_nathan_guest') || '{}');
                if (saved.name)     this.guestName     = saved.name;
                if (saved.whatsapp) this.guestWhatsapp = saved.whatsapp;
                if (saved.birthday) this.guestBirthday = saved.birthday;
            } catch (_) {}
        },

        isSelected(id) {
            return this.selectedServices.some(s => s.id === id);
        },

        toggleService(id, price, duration, name) {
            const idx = this.selectedServices.findIndex(s => s.id === id);
            if (idx >= 0) {
                this.selectedServices.splice(idx, 1);
            } else {
                if (this.selectedServices.length >= this.maxServices) return;
                this.selectedServices.push({ id, price, duration, name });
            }
        },

        get totalPrice() {
            return this.selectedServices.reduce((sum, s) => sum + s.price, 0);
        },

        get totalDuration() {
            return this.selectedServices.reduce((sum, s) => sum + s.duration, 0);
        },

        get signalPrice() {
            const base = 5.00;
            if (this.couponValid && this.couponDiscount > 0) {
                return Math.max(parseFloat((base * (1 - this.couponDiscount / 100)).toFixed(2)), 0.01);
            }
            return base;
        },

        get discountedPrice() {
            if (this.couponValid && this.couponDiscount > 0) {
                return Math.max(parseFloat((this.totalPrice * (1 - this.couponDiscount / 100)).toFixed(2)), 0.01);
            }
            return this.totalPrice;
        },

        aplicarCupom() {
            const c = this.couponCode.trim().toUpperCase();
            if (!c) return;

            fetch('{{ route('coupons.validate') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ code: c }),
            })
            .then(r => r.json())
            .then(data => {
                this.couponValid   = data.valid;
                this.couponMessage = data.valid ? '✅ ' + data.message : '❌ ' + data.message;
                if (data.valid) {
                    this.couponDiscount  = data.discount_percent;
                    this.couponAnimating = true;
                    setTimeout(() => { this.couponAnimating = false; }, 700);
                } else {
                    this.couponDiscount = 0;
                }
            })
            .catch(() => {
                this.couponValid   = false;
                this.couponMessage = '❌ Erro ao validar cupom.';
                this.couponDiscount = 0;
            });
        },

        removerCupom() {
            this.couponCode     = '';
            this.couponValid    = false;
            this.couponDiscount = 0;
            this.couponMessage  = '';
        },

        reloadWithServices(date) {
            const ids = this.selectedServices.map(s => s.id).join(',');
            const url = new URL(window.location.href);
            url.searchParams.set('date', date || '{{ $selectedDate }}');
            if (ids) url.searchParams.set('services', ids);
            else url.searchParams.delete('services');
            window.location.href = url.toString();
        },

        submitGuestForm() {
            this.guestNameError = !this.guestName.trim();
            if (this.guestNameError) return;

            // Salva no localStorage apenas os campos preenchidos
            if (this.rememberGuest) {
                const toSave = {};
                if (this.guestName.trim())     toSave.name     = this.guestName.trim();
                if (this.guestWhatsapp.trim()) toSave.whatsapp = this.guestWhatsapp.trim();
                if (this.guestBirthday)         toSave.birthday = this.guestBirthday;
                localStorage.setItem('barber_nathan_guest', JSON.stringify(toSave));
            }

            const form = document.getElementById('formAgendamento');

            const addHidden = (name, value) => {
                const el = document.createElement('input');
                el.type  = 'hidden';
                el.name  = name;
                el.value = value;
                form.appendChild(el);
            };

            addHidden('guest_name', this.guestName.trim());
            addHidden('guest_whatsapp', this.guestWhatsapp.trim());
            addHidden('guest_birthday', this.guestBirthday);

            form.submit();
        },
    };
}
</script>


</body>
</html>
