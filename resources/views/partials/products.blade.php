<!-- resources/views/partials/products.blade.php -->
<section id="produtos" class="bg-white py-16 md:py-24 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-8">

        <div class="max-w-4xl mr-auto md:mx-auto mb-12 md:mb-16 text-left md:text-center">
            <h2 class="font-barlow font-extrabold text-[40px] md:text-5xl text-[#1A1C1E] uppercase tracking-tighter mb-6 leading-none">
                Produtos
            </h2>
            <p class="font-work-sans text-[#1A1C1E]/70 text-base md:text-lg leading-relaxed max-w-[750px] md:mx-auto">
                Leve a experiência da barbearia para sua casa. Nossa linha de produtos foi selecionada para garantir que seu cabelo e barba continuem impecáveis.
            </p>
        </div>

        <div class="relative max-w-6xl mx-auto">

            <!-- SETAS PADRONIZADAS -->
            <button onclick="moveCarousel(-1)"
                    class="absolute left-0 lg:-left-12 top-[180px] md:top-[210px] -translate-y-1/2 z-40 bg-white shadow-xl w-12 h-12 md:w-14 md:h-14 flex items-center justify-center rounded-full border border-zinc-100 hover:bg-zinc-50 hover:scale-110 transition-all">
                <i class="fas fa-chevron-left text-lg md:text-xl text-[#D4AF37]"></i>
            </button>

            <button onclick="moveCarousel(1)"
                    class="absolute right-0 lg:-right-12 top-[180px] md:top-[210px] -translate-y-1/2 z-40 bg-white shadow-xl w-12 h-12 md:w-14 md:h-14 flex items-center justify-center rounded-full border border-zinc-100 hover:bg-zinc-50 hover:scale-110 transition-all">
                <i class="fas fa-chevron-right text-lg md:text-xl text-[#D4AF37]"></i>
            </button>

            <!-- Janela de Visualização -->
            <div class="overflow-hidden py-10 px-2">
                <div id="product-track" class="flex gap-4 md:gap-8 items-center transition-transform duration-500 ease-in-out">

                    {{-- Aqui injetamos os produtos que vêm do ServiceController --}}
                    @foreach($products as $p)
                        <div class="product-card min-w-[85%] md:min-w-[45%] lg:min-w-[31%] transition-all duration-500 scale-90 opacity-40">
                            <div class="bg-white rounded-xl p-4 shadow-sm border border-zinc-100 relative">
                                <div class="bg-gradient-to-b from-[#A1887F] to-[#4E342E] rounded-lg p-6 mb-4 aspect-square flex items-center justify-center overflow-hidden">
                                    {{-- Ajustado para a sua pasta public/images --}}
                                    <img src="{{ asset('images/' . $p->image) }}" alt="{{ $p->name }}" class="w-full h-full object-contain">
                                </div>
                                <div class="flex justify-between items-start mb-2 text-left h-12">
                                    <h4 class="font-barlow font-bold text-sm md:text-lg text-[#1A1C1E] uppercase leading-tight line-clamp-2">{{ $p->name }}</h4>
                                    <div class="flex items-center gap-1 text-[10px] font-bold shrink-0">
                                        <span class="text-[#D4AF37]">★</span> <span class="text-zinc-600">4.9</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-4">
                                    {{-- Preço real do banco agora aparece aqui --}}
                                    <span class="font-barlow font-extrabold text-xl text-[#1A1C1E]">R$ {{ number_format($p->price, 2, ',', '.') }}</span>
                                    <button class="bg-[#717171] hover:bg-[#1A1C1E] text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>

<style>
    #product-track { will-change: transform; }
    .product-card.active {
        scale: 1.05 !important;
        opacity: 1 !important;
        z-index: 10;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
</style>

<script>
    {{-- Mantive o seu script original exatamente como estava --}}
    const track = document.getElementById('product-track');

    window.addEventListener('load', () => {
        let originalCards = Array.from(track.querySelectorAll('.product-card'));
        const gap = window.innerWidth >= 768 ? 32 : 16;

        // Limpa clones antigos se houver e clona os novos vindos do banco
        originalCards.forEach(card => {
            let cloneBefore = card.cloneNode(true);
            let cloneAfter = card.cloneNode(true);
            track.appendChild(cloneAfter);
            track.prepend(cloneBefore);
        });

        let currentIndex = originalCards.length;
        let isTransitioning = false;

        function getCardWidth() {
            return track.querySelector('.product-card').offsetWidth + gap;
        }

        function updateLayout(smooth = true) {
            const cardWidth = getCardWidth();
            const containerWidth = track.parentElement.offsetWidth;
            const offset = (containerWidth / 2) - (cardWidth / 2);

            track.style.transition = smooth ? "transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)" : "none";
            track.style.transform = `translateX(${-currentIndex * cardWidth + offset}px)`;

            const allCards = track.querySelectorAll('.product-card');
            allCards.forEach((card, index) => {
                if (index === currentIndex) {
                    card.classList.add('active');
                } else {
                    card.classList.remove('active');
                }
            });
        }

        window.moveCarousel = function(direction) {
            if (isTransitioning) return;
            isTransitioning = true;
            currentIndex += direction;
            updateLayout();

            setTimeout(() => {
                const totalOriginal = originalCards.length;
                if (currentIndex >= totalOriginal * 2) {
                    currentIndex = totalOriginal;
                    updateLayout(false);
                } else if (currentIndex < totalOriginal) {
                    currentIndex = totalOriginal * 2 - 1;
                    updateLayout(false);
                }
                isTransitioning = false;
            }, 500);
        }

        updateLayout(false);
        window.addEventListener('resize', () => updateLayout(false));
    });
</script>
