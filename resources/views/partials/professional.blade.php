<!-- resources/views/partials/professional.blade.php -->
<section id="profissional" class="bg-[#F8F5EF] pt-12 pb-0 md:pt-24 md:pb-0 lg:pt-32 lg:pb-0">
    <div class="max-w-7xl mx-auto px-6 md:px-10 lg:px-8">
        <!-- Container: Empilhado no Mobile, Lado a lado no TABLET e Desktop -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-8 md:gap-12 lg:gap-20">

            <!-- COLUNA DE TEXTO -->
            <div class="w-full md:w-1/2 text-left">
                <!-- Título: Reduzido no Tablet (md:text-[2.2rem]) para caber legal -->
                <h2 class="font-barlow font-extrabold text-[2.5rem] md:text-[2.2rem] lg:text-[3.8rem] text-[#1A1C1E] leading-[1.05] uppercase mb-4 md:mb-6 tracking-tighter">
                    UM NOVO CONCEITO <br>
                    EM BARBEARIA <br>
                    EXCLUSIVA
                </h2>

                <!-- Descrição: Fonte menor no tablet para manter a proporção -->
                <p class="font-work-sans text-[#1A1C1E]/80 text-sm md:text-[13px] lg:text-lg leading-relaxed mb-6 md:mb-10 max-w-[480px]">
                    Mais do que um corte, oferecemos uma consultoria de imagem completa. Combinamos técnicas tradicionais com as tendências modernas.
                </p>

                <!-- Estatísticas: Mantendo o alinhamento à esquerda no tablet -->
                <div class="flex flex-row gap-8 md:gap-10 lg:gap-16">
                    <div class="flex flex-col">
                        <span class="font-barlow font-extrabold text-3xl md:text-3xl lg:text-5xl text-[#1A1C1E] mb-1">
                            100<span class="text-dark-vanilla opacity-40">%</span>
                        </span>
                        <span class="font-barlow font-bold text-[10px] lg:text-xs text-[#1A1C1E] uppercase tracking-widest leading-tight">
                            Clientes <br> Satisfeitos
                        </span>
                    </div>

                    <div class="flex flex-col">
                        <span class="font-barlow font-extrabold text-3xl md:text-3xl lg:text-5xl text-[#1A1C1E] mb-1">
                            10<span class="text-dark-vanilla opacity-40">+</span>
                        </span>
                        <span class="font-barlow font-bold text-[10px] lg:text-xs text-[#1A1C1E] uppercase tracking-widest leading-tight">
                            Anos de <br> Experiência
                        </span>
                    </div>
                </div>
            </div>

            <!-- COLUNA DE IMAGEM -->
            <div class="w-full lg:w-1/2 flex justify-center lg:justify-end mt-10 md:mt-0">
                <div class="relative group w-full flex justify-center">

                    <!-- CONTAINER DA IMAGEM COM SOMBRA SUTIL -->
                    <!-- Troquei shadow-2xl por shadow-md para uma sombra mais leve e elegante -->
                    <div class="w-[90%] md:w-[340px] lg:w-[480px] aspect-square md:aspect-[4/5] overflow-hidden shadow-md group-hover:shadow-lg transition-shadow duration-500">
                        <img src="{{ asset('images/Nathan-trabalhando.webp') }}"
                             alt="Nathan em ação"
                             class="w-full h-full object-cover grayscale-[20%] group-hover:grayscale-0 transition-all duration-500 scale-110 md:scale-100">
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
