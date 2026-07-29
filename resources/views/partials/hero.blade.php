<!-- resources/views/partials/hero.blade.php -->
<section id="hero" class="relative" x-data="{
    activeSlide: 1,
    init() {
        setInterval(() => {
            this.activeSlide = this.activeSlide === 1 ? 2 : 1;
        }, 6000);
    }
}">
    <!-- HERO CONTENT -->
<<<<<<< HEAD
    <div class="relative min-h-[600px] sm:min-h-[560px] md:min-h-[520px] lg:min-h-[620px] flex items-center bg-[#0A0A0A] overflow-hidden px-6 md:px-12">

        <!-- Background Slider: ocupa 100% da largura, sem faixas pretas nas laterais em telas largas -->
        <div class="absolute inset-0 z-0">
            <div class="relative w-full h-full">
                <div x-show="activeSlide === 1"
                     x-transition:enter="transition opacity duration-[2000ms] ease-in-out"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition opacity duration-[2000ms] ease-in-out"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0">
                    {{-- OTIMIZADO: fetchpriority e loading para acelerar o LCP --}}
                    <img src="{{ asset('images/hero-bg.webp') }}"
                         fetchpriority="high"
                         loading="eager"
                         class="w-full h-full object-cover object-[85%_15%] lg:object-[85%_35%] scale-105 md:scale-100 opacity-80 brightness-110 grayscale-[10%]"
                         alt="Banner 1">
                </div>

                <div x-show="activeSlide === 2"
                     x-transition:enter="transition opacity duration-[2000ms] ease-in-out"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition opacity duration-[2000ms] ease-in-out"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0"
                     x-cloak>
                    {{-- OTIMIZADO: loading lazy para não pesar no carregamento inicial --}}
                    <img src="{{ asset('images/hero-bg2.webp') }}"
                         loading="lazy"
                         class="w-full h-full object-cover object-center lg:object-[center_25%] opacity-80 brightness-110 grayscale-[10%]"
                         alt="Banner 2">
                </div>

                <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-black/70 z-10"></div>
            </div>
=======
    <div class="relative min-h-[75vh] md:min-h-[60vh] lg:min-h-[70vh] flex items-center bg-[#0A0A0A] overflow-hidden px-6 md:px-12">

        <!-- Background Slider -->
        <div class="absolute inset-0 z-0">
            <div x-show="activeSlide === 1"
                 x-transition:enter="transition opacity duration-[2000ms] ease-in-out"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition opacity duration-[2000ms] ease-in-out"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0">
                <img src="{{ asset('images/hero-bg.png') }}"
                     class="w-full h-full object-cover object-[85%_15%] scale-105 md:scale-100 opacity-80 brightness-110 grayscale-[10%]"
                     alt="Banner 1">
            </div>

            <div x-show="activeSlide === 2"
                 x-transition:enter="transition opacity duration-[2000ms] ease-in-out"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition opacity duration-[2000ms] ease-in-out"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0">
                <img src="{{ asset('images/hero-bg2.png') }}"
                     class="w-full h-full object-cover object-center opacity-80 brightness-110 grayscale-[10%]"
                     alt="Banner 2">
            </div>

            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-black/70 z-10"></div>
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
        </div>

        <!-- CONTEÚDO PRINCIPAL -->
        <div class="relative z-20 w-full max-w-7xl mx-auto pt-10 md:pt-16 text-left md:text-center">
            <div class="max-w-[320px] md:max-w-4xl md:mx-auto">
                <h2 class="font-barlow font-extrabold text-[2.8rem] md:text-[3.8rem] lg:text-6xl
<<<<<<< HEAD
                            leading-[1.3] md:leading-[1.15]
                            text-white uppercase tracking-tight mb-4">
=======
                           leading-[1.3] md:leading-[1.15]
                           text-white uppercase tracking-tight mb-4">
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                    NATHAN <br class="block md:hidden">
                    DO CORTE <br>
                    SEU ESTILO <br class="block md:hidden">
                    COMEÇA AQUI
                </h2>

                <div class="flex justify-end md:justify-center mb-8">
                    <p class="font-work-sans text-white/90 md:text-white/80 text-[15px] md:text-base uppercase tracking-[0.2em] italic leading-tight text-right md:text-center max-w-[180px] md:max-w-none">
                        Corte, barba e experiência de alto nível.
                    </p>
                </div>

                <!-- BOTÃO CENTRALIZADO NO MOBILE -->
                <div class="flex flex-col md:flex-row items-center md:justify-center gap-4 w-full">
                    <a href="{{ route('appointments.create', ['service' => 'geral']) }}"
                       class="w-[85%] md:w-64 bg-dark-vanilla hover:bg-[#c9b394] text-[#1A1C1E] font-barlow font-extrabold px-8 py-4 transition-all duration-300 uppercase tracking-widest text-[11px] shadow-xl text-center block mx-auto md:mx-0">
                        AGENDAR AGORA
                    </a>

                    <a href="#servicos" class="hidden md:inline-block w-full md:w-64 border border-white/50 text-white hover:bg-white hover:text-[#1A1C1E] font-barlow font-extrabold px-10 py-3.5 transition-all duration-300 uppercase tracking-widest text-[11px] text-center">
                        VER SERVIÇOS
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- INFOBOX -->
    <div class="relative z-30 max-w-6xl mx-auto px-4 -mt-10 md:-mt-14 lg:-mt-16 shadow-2xl">
        <div class="bg-white grid grid-cols-2 md:grid-cols-3 overflow-hidden">
            <!-- ENDEREÇO -->
            <div class="flex flex-col items-center text-center p-6 md:p-8 lg:p-10 order-1 border-r border-zinc-100">
                <i class="fas fa-map-marker-alt text-xl text-dark-vanilla mb-3"></i>
                <h4 class="font-barlow font-extrabold uppercase text-[9px] tracking-[0.2em] text-zinc-400 mb-2">Endereço</h4>
                <div class="w-8 h-[1px] bg-dark-vanilla mb-3 mx-auto"></div>
                <p class="font-work-sans text-[10px] md:text-[11px] font-bold text-zinc-800 uppercase leading-relaxed">
                    R. ZAQUEL FERREIRA DO VALE, 1462 - <br class="hidden lg:block"> CASCAVEL, CE
                </p>
            </div>

            <!-- TELEFONE -->
            <div class="col-span-2 md:col-span-1 flex flex-col items-center text-center p-6 md:p-8 lg:p-10 bg-[#FBFBFB] order-3 md:order-2 border-t md:border-t-0 border-zinc-100">
                <i class="fas fa-phone-alt text-xl text-dark-vanilla mb-3"></i>
                <h4 class="font-barlow font-extrabold uppercase text-[9px] tracking-[0.2em] text-zinc-400 mb-2">Telefone</h4>
                <div class="w-8 h-[1px] bg-dark-vanilla mb-3 mx-auto"></div>
                <p class="font-work-sans text-[11px] md:text-[13px] font-bold text-zinc-800 leading-relaxed">
                    <a href="https://wa.me/5585986839615" target="_blank" class="hover:text-dark-vanilla transition-colors">
                        +55 (85) 98683-9615
                    </a>
                </p>
            </div>

            <!-- HORÁRIOS -->
            <div class="flex flex-col items-center text-center p-6 md:p-8 lg:p-10 order-2 md:order-3">
                <i class="far fa-clock text-xl text-dark-vanilla mb-3"></i>
                <h4 class="font-barlow font-extrabold uppercase text-[9px] tracking-[0.2em] text-zinc-400 mb-2">Horários</h4>
                <div class="w-8 h-[1px] bg-dark-vanilla mb-3 mx-auto"></div>
                <div class="font-work-sans text-[10px] md:text-[11px] font-bold text-zinc-800 uppercase leading-tight space-y-1">
                    <p>TER - SEX: 08:30H — 18:00H</p>
                    <p>SÁB: 08:00H — 17:00H</p>
                </div>
            </div>
        </div>
    </div>
</section>
