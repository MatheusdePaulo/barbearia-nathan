<!-- resources/views/partials/footer.blade.php -->
<section id="contato" class="relative overflow-hidden">

    <!-- 1. BLOCO DE AGENDAMENTO -->
<<<<<<< HEAD
    <div class="relative min-h-[560px] sm:min-h-[600px] md:min-h-[550px] bg-[#0A0A0A] flex items-start pt-10 md:items-center md:pt-0">

        <!-- Imagem de fundo travada em largura máxima: não estica em telas ultra largas (zoom out), sobra fundo preto nas laterais -->
        <div class="absolute inset-0 flex justify-center">
            <div class="relative w-full h-full max-w-[1600px] bg-cover bg-center"
                 style="background-image: url('{{ asset('images/Nathan-trabalhando.webp') }}');"></div>
        </div>

        <div class="absolute inset-0 bg-black/75"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-12 w-full py-14 sm:py-16 md:py-20">
            <div class="text-left space-y-6">
                <h2 class="font-barlow font-extrabold text-3xl sm:text-[40px] md:text-5xl text-white uppercase leading-[0.9] tracking-tighter">
=======
    <div class="relative min-h-[750px] md:min-h-[550px] bg-cover bg-center flex items-start pt-10 md:items-center md:pt-0"
         style="background-image: url('{{ asset('images/Nathan-trabalhando.png') }}');">

        <div class="absolute inset-0 bg-black/75"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-12 w-full py-20">
            <div class="text-left space-y-6">
                <h2 class="font-barlow font-extrabold text-[40px] md:text-5xl text-white uppercase leading-[0.9] tracking-tighter">
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                    Agendar <br> Horário
                </h2>
                <p class="font-work-sans text-white/80 text-sm md:text-base max-w-md leading-relaxed">
                    Solicite seu atendimento exclusivo fora do horário comercial ou no conforto da sua casa. Para sua comodidade, aplicamos uma taxa fixa de R$ 25,00 para deslocamento ou abertura antecipada da barbearia.
                </p>

                <div class="space-y-6 pt-4">
                    <div class="flex items-center gap-4">
<<<<<<< HEAD
                        <div class="w-12 h-12 shrink-0 bg-white flex items-center justify-center rounded-sm text-[#1A1C1E]">
=======
                        <div class="w-12 h-12 bg-white flex items-center justify-center rounded-sm text-[#1A1C1E]">
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <p class="text-white font-barlow font-extrabold text-lg">(85) 98683-9615</p>
                    </div>
                    <div class="flex items-center gap-4">
<<<<<<< HEAD
                        <div class="w-12 h-12 shrink-0 bg-white flex items-center justify-center rounded-sm text-[#1A1C1E]">
=======
                        <div class="w-12 h-12 bg-white flex items-center justify-center rounded-sm text-[#1A1C1E]">
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                            <i class="fas fa-envelope"></i>
                        </div>
                        <p class="text-white font-barlow font-extrabold text-lg break-all">contato@nathandocorte.com.br</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. FORMULÁRIO SOBREPOSTO -->
    <!-- Restaurado: md:h-0 faz com que no desktop o container não ocupe espaço físico, permitindo que o absolute ancore no topo da seção -->
    <div class="relative z-30 max-w-7xl mx-auto px-0 md:px-6 md:h-0">
<<<<<<< HEAD
        <div class="-mt-32 sm:-mt-40 md:mt-0 md:absolute md:right-6 lg:right-12 md:top-0 md:-translate-y-1/2 w-[90%] mx-auto md:w-full md:max-w-md lg:max-w-lg bg-white p-5 md:p-10 shadow-2xl rounded-sm border-t-4 border-[#DEC7A6]">
=======
        <div class="-mt-56 md:mt-0 md:absolute md:right-6 lg:right-12 md:top-0 md:-translate-y-1/2 w-[90%] mx-auto md:w-full md:max-w-md lg:max-w-lg bg-white p-5 md:p-10 shadow-2xl rounded-sm border-t-4 border-[#DEC7A6]">
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
            <form action="#" method="POST" class="space-y-6">
                @csrf
                <input type="text" name="nome" placeholder="NOME COMPLETO" required class="w-full border-b border-zinc-200 py-3 font-barlow font-bold text-xs tracking-widest focus:border-[#DEC7A6] outline-none transition-colors uppercase">
                <input type="text" name="assunto" placeholder="ASSUNTO" required class="w-full border-b border-zinc-200 py-3 font-barlow font-bold text-xs tracking-widest focus:border-[#DEC7A6] outline-none transition-colors uppercase">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <input type="text" name="whatsapp" placeholder="WHATSAPP" required class="w-full border-b border-zinc-200 py-3 font-barlow font-bold text-xs tracking-widest focus:border-[#DEC7A6] outline-none transition-colors uppercase">
                    <input type="email" name="email" placeholder="SEU MELHOR E-MAIL" required class="w-full border-b border-zinc-200 py-3 font-barlow font-bold text-xs tracking-widest focus:border-[#DEC7A6] outline-none transition-colors uppercase">
                </div>
                <textarea name="mensagem" rows="3" placeholder="ESCREVA SUA MENSAGEM AQUI..." required class="w-full border-b border-zinc-200 py-3 font-barlow font-bold text-xs tracking-widest focus:border-[#DEC7A6] outline-none transition-colors uppercase resize-none"></textarea>

                <button type="submit" class="w-full bg-[#DEC7A6] hover:bg-[#c9b394] text-[#1A1C1E] font-barlow font-black py-5 uppercase tracking-[0.2em] text-xs transition-all shadow-lg active:scale-95">
                    Solicitar Agendamento
                </button>
            </form>
        </div>
    </div>

    <!-- 3. MAPA -->
<<<<<<< HEAD
    <div class="relative z-20 -mt-16 sm:-mt-20 md:mt-0 w-full h-[350px] sm:h-[400px] md:h-[500px] grayscale contrast-125 hover:grayscale-0 transition-all duration-1000 ease-in-out">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3978.834608381!2d-38.24355338523916!3d-4.1345510970002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x7be199e82977461%3A0x868153406456789!2sNathan%20do%20Corte!5e0!3m2!1spt-BR!2sbr!4v1700000000000!5m2!1spt-BR!2sbr&q=Nathan+do+Corte+R.+Zaquiel+Ferreira+do+Vale+1462+Cascavel+CE"
            class="w-full h-full max-w-full border-0"
=======
    <div class="relative z-20 -mt-72 md:mt-0 w-full h-[800px] md:h-[500px] grayscale contrast-125 hover:grayscale-0 transition-all duration-1000 ease-in-out">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3978.834608381!2d-38.24355338523916!3d-4.1345510970002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x7be199e82977461%3A0x868153406456789!2sNathan%20do%20Corte!5e0!3m2!1spt-BR!2sbr!4v1700000000000!5m2!1spt-BR!2sbr&q=Nathan+do+Corte+R.+Zaquiel+Ferreira+do+Vale+1462+Cascavel+CE"
            class="w-full h-full border-0"
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
            allowfullscreen=""
            loading="lazy">
        </iframe>
    </div>

    <!-- 4. FOOTER -->
    <footer class="relative z-40 bg-[#141517] py-5 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="font-work-sans text-[10px] text-white/30 uppercase tracking-[0.2em] text-center md:text-left">
                © 2026 NATHAN DO CORTE - DESENVOLVIDO POR
                <a href="#" target="_blank" class="text-white/60 hover:text-[#DEC7A6] transition-colors font-bold">
                    MATHEUS DE PAULO
                </a>
            </p>
            <div class="flex items-center gap-8">
                <a href="https://www.instagram.com/nathan_do_cortte/" target="_blank" class="text-white/40 hover:text-[#DEC7A6] transition-colors text-base"><i class="fab fa-instagram"></i></a>
                <a href="https://wa.link/kjfafg" target="_blank" class="text-white/40 hover:text-[#DEC7A6] transition-colors text-base"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </footer>
</section>
