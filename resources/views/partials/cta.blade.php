<!-- resources/views/partials/cta.blade.php -->
<section id="agendar-sessao" class="relative w-full overflow-hidden">
    <!-- Background Image com Overlay -->
<<<<<<< HEAD
    <div class="relative min-h-[420px] sm:min-h-[480px] md:min-h-[400px] lg:min-h-[450px] w-full flex items-center justify-center bg-[#0A0A0A] py-16 md:py-0">

        <!-- Imagem de fundo ocupa 100% da largura, sem faixas pretas nas laterais em telas largas -->
        <div class="absolute inset-0 bg-cover bg-center"
             style="background-image: url('{{ asset('images/Nathan-trabalhando.webp') }}');"></div>
=======
    <div class="relative min-h-[650px] md:min-h-[400px] lg:min-h-[450px] w-full flex items-center justify-center bg-cover bg-center"
         style="background-image: url('{{ asset('images/Nathan-trabalhando.png') }}');">
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b

        <!-- Overlay Gradiente para legibilidade -->
        <div class="absolute inset-0 bg-black/50 lg:bg-black/40"></div>

        <!-- Conteúdo -->
<<<<<<< HEAD
        <div class="relative z-10 container mx-auto px-6 max-w-full">
=======
        <div class="relative z-10 container mx-auto px-6">
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
            <!-- Container: Alinhamento de itens (botão) centralizado no mobile | Texto controlado individualmente -->
            <div class="max-w-4xl mr-auto md:mx-auto flex flex-col items-center md:items-center">

                <!-- Título: Forçado à esquerda no mobile (self-start) e centro no desktop -->
<<<<<<< HEAD
                <h2 class="self-start md:self-auto font-barlow font-extrabold text-3xl sm:text-[36px] md:text-[40px] lg:text-[52px] text-white uppercase text-left md:text-center leading-[1.4] md:leading-tight tracking-tight mb-8 sm:mb-10 md:mb-12 max-w-[320px] sm:max-w-sm md:max-w-none">
=======
                <h2 class="self-start md:self-auto font-barlow font-extrabold text-[36px] md:text-[40px] lg:text-[52px] text-white uppercase text-left md:text-center leading-[1.5] md:leading-tight tracking-tight mb-10 md:mb-12 max-w-[320px] md:max-w-none">
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                    Muito mais do que um corte, é a sua autoestima renovada, excelência e estilo em cada detalhe.
                </h2>

                <!-- Botão Agendar: Centralizado pelo 'items-center' do pai no mobile -->
                <a href="{{ route('appointments.create', ['service' => 'geral']) }}" class="inline-block bg-dark-vanilla hover:bg-[#c9b394] text-[#1A1C1E] font-barlow font-extrabold px-12 py-4 md:px-14 md:py-5 transition-all duration-300 uppercase tracking-widest text-[13px] md:text-sm shadow-2xl border border-white/10">
                    Agendar Agora
                </a>

            </div>
        </div>
    </div>
</section>
