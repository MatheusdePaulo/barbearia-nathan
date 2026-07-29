<header class="w-full z-50 transition-all duration-300"
        x-data="{ scrolled: false }"
        @scroll.window="scrolled = (window.pageYOffset > 50) ? true : false"
        :class="{
            'fixed top-0 left-0 bg-[#1A1C1E]/60 backdrop-blur-md py-2': scrolled && window.innerWidth >= 1024,
            'relative bg-[#1A1C1E]/80 py-3 lg:py-4': !scrolled || window.innerWidth < 1024
        }">

    <div x-show="!scrolled"
         x-transition:enter="transition ease-out duration-300"
         class="hidden lg:block w-full bg-[#1A1C1E]/80 text-white/80 py-1.5 border-b border-white/10 font-work-sans text-[11px]">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center gap-6">
                <span class="flex items-center gap-2">
                    <i class="fas fa-phone-alt text-dark-vanilla text-[10px]"></i> (85) 98683-9615
                </span>
                <span class="flex items-center gap-2 border-l border-white/20 pl-6">
                    <i class="fas fa-envelope text-dark-vanilla text-[10px]"></i> contato@nathandocorte.com.br
                </span>
            </div>

            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="hover:text-dark-vanilla transition-colors uppercase tracking-widest font-bold cursor-pointer">Entrar</a>
                    <span class="text-white/20">|</span>
                    <a href="{{ route('register') }}" class="hover:text-dark-vanilla transition-colors uppercase tracking-widest font-bold cursor-pointer">Criar conta</a>
                @endguest

                @auth
                    <span class="text-dark-vanilla font-bold uppercase tracking-widest italic">Olá, {{ Auth::user()->name }}</span>
                    <span class="text-white/20">|</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-red-500 transition-colors uppercase tracking-widest font-bold">Sair</button>
                    </form>
                @endauth
                <i class="fas fa-user-circle text-[10px] ml-1 text-dark-vanilla"></i>
            </div>
        </div>
    </div>

    <nav class="w-full transition-all duration-300" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">

            <div class="flex-shrink-0">
                <a href="/#hero">
                    <img src="{{ asset('images/logotipo_nathan.webp') }}"
                         alt="Barber Nathan"
                         class="h-10 lg:h-14 w-40 lg:w-48 object-contain">
                </a>
            </div>

            <button @click="mobileMenuOpen = true" class="lg:hidden flex items-center gap-3 text-white p-2 hover:bg-white/5 transition-colors rounded-lg">
                <span class="font-barlow font-bold uppercase tracking-widest text-xs text-[#DEC7A6]">Menu</span>
                <i class="fas fa-bars text-2xl md:text-3xl"></i>
            </button>

            <div class="hidden lg:flex items-center gap-10">
                <ul class="flex items-center gap-8 font-work-sans text-white text-[12px] uppercase tracking-[0.15em]">
                    <li><a href="/#hero" class="hover:text-dark-vanilla transition-colors">Início</a></li>
                    <li><a href="/#profissional" class="hover:text-dark-vanilla transition-colors">Sobre</a></li>
                    <li><a href="/#servicos" class="hover:text-dark-vanilla transition-colors">Serviços</a></li>
                    <li><a href="/#agendar-sessao" class="hover:text-dark-vanilla transition-colors">Agendar</a></li>
                    <li><a href="/#produtos" class="hover:text-dark-vanilla transition-colors">Produtos</a></li>
                    <li><a href="/#contato" class="hover:text-dark-vanilla transition-colors">Contato</a></li>
                </ul>

                <a href="{{ route('appointments.create', ['service' => 'geral']) }}" class="font-barlow font-extrabold text-white border border-dark-vanilla px-5 py-1.5 rounded-full hover:bg-dark-vanilla hover:text-[#1A1C1E] transition-all duration-300 uppercase text-[11px] tracking-tighter">
                    AGENDAR AGORA
                </a>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-cloak @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/60 z-[60]"></div>

        <div x-show="mobileMenuOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="fixed inset-y-0 right-0 w-[85%] md:w-[450px] bg-[#141517] z-[70] shadow-2xl lg:hidden p-8 md:p-12 overflow-y-auto">

            <div class="flex flex-col h-full">
                <div class="relative flex flex-col items-center mb-10">
                    <button @click="mobileMenuOpen = false" class="absolute right-0 top-0 text-white p-2">
                        <i class="fas fa-times text-2xl md:text-3xl"></i>
                    </button>
                    <div class="mt-6">
                        <img src="{{ asset('images/logotipo_nathan.webp') }}" alt="Barber Nathan" class="w-44 md:w-60 h-auto object-contain">
                    </div>
                </div>

                <ul class="flex flex-col gap-6 md:gap-8 font-work-sans text-white text-sm md:text-lg uppercase tracking-[0.2em]">
                    <li class="flex items-center gap-4 hover:text-dark-vanilla transition-colors">
                        <i class="fas fa-home w-8 text-center text-dark-vanilla"></i> <a href="/#hero" @click="mobileMenuOpen = false">Início</a>
                    </li>
                    <li class="flex items-center gap-4 hover:text-dark-vanilla transition-colors">
                        <i class="far fa-user w-8 text-center text-dark-vanilla"></i> <a href="/#profissional" @click="mobileMenuOpen = false">Sobre</a>
                    </li>
                    <li class="flex items-center gap-4 hover:text-dark-vanilla transition-colors">
                        <i class="fas fa-cut w-8 text-center text-dark-vanilla"></i> <a href="/#servicos" @click="mobileMenuOpen = false">Serviços</a>
                    </li>
                    <li class="flex items-center gap-4 hover:text-dark-vanilla transition-colors">
                        <i class="far fa-calendar-alt w-8 text-center text-dark-vanilla"></i> <a href="/#agendar-sessao" @click="mobileMenuOpen = false">Agendar</a>
                    </li>
                    <li class="flex items-center gap-4 hover:text-dark-vanilla transition-colors">
                        <i class="fas fa-shopping-bag w-8 text-center text-dark-vanilla"></i> <a href="/#produtos" @click="mobileMenuOpen = false">Produtos</a>
                    </li>
                    <li class="flex items-center gap-4 hover:text-dark-vanilla transition-colors">
                        <i class="fas fa-envelope w-8 text-center text-dark-vanilla"></i> <a href="/#contato" @click="mobileMenuOpen = false">Contato</a>
                    </li>

                    @guest
                        <li class="flex items-center gap-4 pt-4 mt-2 border-t border-white/10">
                            <i class="fas fa-user-circle w-8 text-center text-dark-vanilla"></i>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('login') }}" class="hover:text-dark-vanilla transition-colors uppercase tracking-widest font-bold text-xs">Entrar</a>
                                <span class="text-white/20">|</span>
                                <a href="{{ route('register') }}" class="hover:text-dark-vanilla transition-colors uppercase tracking-widest font-bold text-xs">Criar conta</a>
                            </div>
                        </li>
                    @endguest

                    @auth
                        <li class="flex flex-col gap-4 pt-4 mt-2 border-t border-white/10">
                            <div class="flex items-center gap-4 text-dark-vanilla font-bold italic text-sm">
                                <i class="fas fa-user-check w-8 text-center"></i> <span>{{ Auth::user()->name }}</span>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-4 text-red-500 hover:text-red-400 font-bold uppercase text-[11px] tracking-widest">
                                    <i class="fas fa-sign-out-alt w-8 text-center"></i> Sair do Sistema
                                </button>
                            </form>
                        </li>
                    @endauth
                </ul>

                <hr class="my-8 border-white/10">

                <div class="relative flex flex-col items-center bg-[#1A1C1E] p-6 md:p-10 rounded-2xl border border-white/5 shadow-inner mt-auto"
                     x-data="{ wifiOpen: false, copied: false }">
                    <p class="text-[9px] md:text-[11px] font-barlow font-extrabold text-dark-vanilla mb-3 tracking-[0.3em] uppercase text-center">WIFI DA BARBEARIA</p>

                    <div @click="wifiOpen = true" class="bg-white p-3 md:p-5 rounded-xl block transition-transform hover:scale-105 cursor-pointer shadow-lg">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=WIFI%3AS%3ABarbearia%3BT%3AWPA%3BP%3Ac4me1mvb%3B%3B" alt="QR Code Wi-Fi" class="w-28 md:w-36 h-28 md:h-36">
                    </div>

                    <p class="text-[9px] text-white/40 uppercase font-bold tracking-wider mt-3 text-center">
                        <i class="fas fa-mobile-alt text-dark-vanilla mr-1"></i> Toque no QR Code para ver a senha
                    </p>

                    {{-- Popup com rede e senha --}}
                    <div x-show="wifiOpen" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.away="wifiOpen = false; copied = false"
                         class="absolute bottom-full left-1/2 -translate-x-1/2 mb-3 w-64 bg-[#121212] border border-zinc-700 rounded-2xl shadow-2xl p-5 z-50">

                        <div class="flex justify-between items-center mb-4">
                            <p class="text-[10px] font-black uppercase tracking-widest text-dark-vanilla">Wi-Fi da Barbearia</p>
                            <button @click="wifiOpen = false; copied = false" class="text-zinc-600 hover:text-white transition-colors">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>

                        <div class="space-y-3">
                            <div class="bg-zinc-900 rounded-xl px-4 py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-[9px] text-zinc-500 uppercase font-bold tracking-widest">Rede</p>
                                    <p class="text-white font-black text-sm">Barbearia</p>
                                </div>
                                <i class="fas fa-wifi text-dark-vanilla"></i>
                            </div>

                            <div class="bg-zinc-900 rounded-xl px-4 py-3 flex justify-between items-center gap-3">
                                <div>
                                    <p class="text-[9px] text-zinc-500 uppercase font-bold tracking-widest">Senha</p>
                                    <p class="text-white font-black text-sm font-mono">c4me1mvb</p>
                                </div>
                                <button @click="navigator.clipboard.writeText('c4me1mvb'); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="shrink-0 px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all"
                                        :class="copied ? 'bg-green-500/20 text-green-400' : 'bg-zinc-800 text-dark-vanilla hover:bg-dark-vanilla hover:text-black'">
                                    <i class="fas" :class="copied ? 'fa-check' : 'fa-copy'"></i>
                                    <span x-text="copied ? 'Copiado!' : 'Copiar'"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-center gap-8 mt-8 w-full">
                        <a href="https://www.instagram.com/nathan_do_cortte/" target="_blank" class="transition-transform duration-300 hover:scale-110 block w-12 h-12">
                            <img src="{{ asset('images/insta.webp') }}" alt="Instagram" class="w-full h-full object-cover rounded-xl shadow-lg">
                        </a>
                        <a href="https://wa.me/558586839615" target="_blank" class="transition-transform duration-300 hover:scale-110 block w-12 h-12">
                            <img src="{{ asset('images/zap.webp') }}" alt="WhatsApp" class="w-full h-full object-cover rounded-xl shadow-lg">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
