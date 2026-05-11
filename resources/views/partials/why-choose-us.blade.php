<!-- resources/views/partials/why-choose-us.blade.php -->
<section id="sobre" class="bg-white py-10 md:py-16">
    <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-8">

        <!-- Cabeçalho da Seção -->
        <div class="max-w-4xl mr-auto md:mx-auto text-left md:text-center mb-16 md:mb-20">
            <h2 class="font-barlow font-extrabold text-[40px] md:text-5xl text-[#1A1C1E] uppercase leading-[0.9] tracking-tighter mb-6">
                Por que nos <br class="md:hidden"> escolher
            </h2>
            <p class="font-work-sans text-[#1A1C1E] text-base md:text-lg leading-relaxed max-w-[650px] md:mx-auto">
                Compromisso com a excelência, pontualidade e um estilo que respeita a sua identidade. Descubra por que somos referência em Cascavel.
            </p>
        </div>

        <!-- Grid de Diferenciais -->
        <div class="flex flex-col md:grid md:grid-cols-3 gap-16 md:gap-8 lg:gap-16 mb-20 md:mb-24">
            <!-- Item 1: Confiança -->
            <div class="flex flex-col items-start md:items-center text-left md:text-center group">
                <div class="flex items-center gap-4 mb-3 md:flex-col md:mb-6">
                    <img src="{{ asset('images/confianca.png') }}" alt="Confiança" class="w-10 h-10 md:w-12 md:h-12 object-contain">
                    <h4 class="font-barlow font-extrabold text-xl text-[#1A1C1E] uppercase tracking-tight relative md:mt-4">
                        Confiança
                        <span class="absolute -bottom-1 left-0 md:left-1/2 md:-translate-x-1/2 w-full md:w-8 h-[2px] bg-[#DEC7A6]/50"></span>
                    </h4>
                </div>
                <p class="font-work-sans text-sm text-[#1A1C1E] md:text-[#1A1C1E]/60 leading-relaxed max-w-[280px]">
                    Satisfação garantida e reputação sólida. Cada cliente é único e recebe atenção total na cadeira.
                </p>
            </div>

            <!-- Item 2: Expertise -->
            <div class="flex flex-col items-end md:items-center text-right md:text-center group">
                <div class="flex flex-row-reverse items-center gap-4 mb-3 md:flex-col md:mb-6">
                    <img src="{{ asset('images/expertise.png') }}" alt="Expertise" class="w-10 h-10 md:w-12 md:h-12 object-contain">
                    <h4 class="font-barlow font-extrabold text-xl text-[#1A1C1E] uppercase tracking-tight relative md:mt-4">
                        Expertise
                        <span class="absolute -bottom-1 right-0 md:left-1/2 md:-translate-x-1/2 w-full md:w-8 h-[2px] bg-[#DEC7A6]/50"></span>
                    </h4>
                </div>
                <p class="font-work-sans text-sm text-[#1A1C1E] md:text-[#1A1C1E]/60 leading-relaxed max-w-[280px]">
                    Especialista em cortes modernos e barbas clássicas, focado em entregar o acabamento perfeito.
                </p>
            </div>

            <!-- Item 3: Profissionalismo -->
            <div class="flex flex-col items-start md:items-center text-left md:text-center group">
                <div class="flex items-center gap-4 mb-3 md:flex-col md:mb-6">
                    <img src="{{ asset('images/profissionalismo.png') }}" alt="Profissionalismo" class="w-10 h-10 md:w-12 md:h-12 object-contain">
                    <h4 class="font-barlow font-extrabold text-xl text-[#1A1C1E] uppercase tracking-tight relative md:mt-4">
                        Profissionalismo
                        <span class="absolute -bottom-1 left-0 md:left-1/2 md:-translate-x-1/2 w-full md:w-8 h-[2px] bg-[#DEC7A6]/50"></span>
                    </h4>
                </div>
                <p class="font-work-sans text-sm text-[#1A1C1E] md:text-[#1A1C1E]/60 leading-relaxed max-w-[280px]">
                    Ambiente higienizado e técnicas avançadas para garantir segurança e conforto em cada atendimento.
                </p>
            </div>
        </div>

        <!-- Área de Prova Social -->
        <div class="flex flex-col md:grid md:grid-cols-3 items-center gap-12 md:gap-8 lg:gap-16 w-full">

            <!-- Container das Notas: Lado a lado no mobile, "desaparece" no desktop para respeitar o grid -->
            <div class="flex flex-row md:contents items-center justify-between w-full gap-4 md:gap-0">

                <!-- Google -->
                <div class="flex flex-col items-center text-center flex-1 md:w-auto">
                    <img src="{{ asset('images/google.png') }}" alt="Google" class="w-8 h-8 md:w-10 md:h-10 object-contain mb-3">
                    <p class="font-barlow font-bold text-[10px] md:text-xs text-[#1A1C1E] uppercase tracking-widest mb-1">Google</p>
                    <span class="font-barlow font-black text-4xl md:text-6xl text-[#1A1C1E] leading-none mb-2">5.0</span>
                    <div class="flex text-[#DEC7A6] text-[8px] md:text-[10px] mb-1">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="font-work-sans text-[10px] md:text-[11px] font-medium text-zinc-400 uppercase tracking-widest">3 reviews</p>
                </div>

                <!-- TripAdvisor -->
                <div class="flex flex-col items-center text-center flex-1 md:order-3 md:w-auto">
                    <img src="{{ asset('images/tripadvisor.png') }}" alt="TripAdvisor" class="w-8 h-8 md:w-10 md:h-10 object-contain mb-3">
                    <p class="font-barlow font-bold text-[10px] md:text-xs text-[#1A1C1E] uppercase tracking-widest mb-1">TripAdvisor</p>
                    <span class="font-barlow font-black text-4xl md:text-6xl text-[#1A1C1E] leading-none mb-2">5.0</span>
                    <div class="flex text-[#DEC7A6] text-[8px] md:text-[10px] mb-1">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="font-work-sans text-[10px] md:text-[11px] font-medium text-zinc-400 uppercase tracking-widest">3 reviews</p>
                </div>
            </div>

            <!-- Depoimento Central (Embaixo das notas no mobile, centro do grid no desktop) -->
            <div class="relative w-full max-w-md bg-white border border-[#DEC7A6]/30 p-8 pt-12 mx-auto md:order-2">
                <div class="absolute -top-10 left-1/2 -translate-x-1/2 w-20 h-20 rounded-full border-4 border-white shadow-lg overflow-hidden z-10">
                    <img src="{{ asset('images/matheus.png') }}" alt="Matheus de Paulo" class="w-full h-full object-cover">
                </div>
                <div class="text-center space-y-4">
                    <div class="flex justify-center text-[#DEC7A6] text-sm">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <h5 class="font-barlow font-extrabold text-lg text-[#1A1C1E] uppercase tracking-tight italic">O melhor da região</h5>
                    <p class="font-work-sans text-[13px] text-[#1A1C1E]/70 leading-relaxed max-w-[320px] mx-auto">
                        "Atendimento impecável e muita precisão no corte. O Nathan entende exatamente o que você pede e o ambiente é muito massa. Recomendo demais!"
                    </p>
                    <p class="font-barlow font-black text-[11px] text-[#1A1C1E] uppercase tracking-[0.2em]">Matheus de Paulo</p>
                </div>
            </div>

        </div>
    </div>
</section>
