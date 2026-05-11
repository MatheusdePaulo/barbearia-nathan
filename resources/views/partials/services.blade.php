<section id="servicos" class="bg-[#F8F5EF] pt-16 pb-16 md:pt-24 md:pb-24">
    <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-8">

        <div class="max-w-4xl mr-auto md:mx-auto mb-12 md:mb-20 text-left md:text-center">
            <h2 class="font-barlow font-extrabold text-4xl md:text-5xl text-[#1A1C1E] uppercase tracking-tighter mb-6">
                Nossos Serviços
            </h2>
            <p class="font-work-sans text-[#1A1C1E] text-base md:text-lg leading-relaxed max-w-[750px] md:mx-auto">
                Explore nossa lista de serviços exclusivos. Combinamos técnicas <br class="hidden md:block">
                tradicionais com as tendências mais modernas para garantir que cada <br class="hidden md:block">
                detalhe do seu visual reflita sua personalidade e estilo único.
            </p>
        </div>

        <div class="bg-white shadow-xl rounded-sm p-8 md:p-12 lg:p-24 max-w-[1000px] mx-auto mb-16">
            {{-- Ajustamos o gap-x para ser menor no tablet (md) e maior no desktop (lg) para evitar sobreposição --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 lg:gap-x-20 gap-y-10 md:gap-y-16 lg:gap-y-24">

                {{-- Loop dinâmico que percorre os 10 serviços do banco --}}
                @foreach($services as $service)
                    <div class="flex items-start gap-4 md:gap-5 lg:gap-6 group">
                        {{-- Imagem encolhe levemente no tablet para dar espaço ao texto --}}
                        <img src="{{ asset('images/' . $service->image) }}"
                             class="w-10 h-10 md:w-12 lg:w-14 md:h-12 lg:h-14 object-contain grayscale group-hover:grayscale-0 transition-all shrink-0"
                             alt="{{ $service->name }}">

                        <div class="text-left">
                            {{-- Ajuste de fonte no tablet (md:text-lg) para não empurrar o layout --}}
                            <h4 class="font-barlow font-extrabold text-xl md:text-lg lg:text-2xl text-[#1A1C1E] uppercase leading-none mb-2 tracking-tight">
                                {{ $service->name }}
                            </h4>
                            {{-- Controle de largura máxima do texto no tablet para manter o alinhamento vertical --}}
                            <p class="font-work-sans text-xs lg:text-sm text-[#1A1C1E]/60 mb-3 max-w-[200px] md:max-w-[180px] lg:max-w-[220px] leading-snug">
                                {{ $service->description }}
                            </p>
                            <span class="font-barlow font-extrabold text-xl lg:text-2xl text-[#1A1C1E]">
                                R$ {{ number_format($service->price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <div class="flex justify-center">
            <a href="{{ route('appointments.create', ['service' => 'geral']) }}"
               class="inline-block bg-[#DEC7A6] hover:bg-[#c9b394] text-[#1A1C1E] font-barlow font-extrabold px-14 py-5 transition-all duration-300 uppercase tracking-widest text-sm shadow-lg">
                Agendar Agora
            </a>
        </div>
    </div>
</section>
