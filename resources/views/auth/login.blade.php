<x-guest-layout>
    <div class="min-h-screen bg-[#050505] flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            {{-- HEADER COM IDENTIDADE --}}
            <div class="text-center mb-10">
                <a href="/" class="inline-block mb-6">
                    <img src="{{ asset('images/logotipo_nathan.png') }}" alt="Barber Nathan" class="h-20 w-auto mx-auto">
                </a>
                <h1 class="text-2xl font-black italic text-white uppercase tracking-tighter">Entrar na Barbearia</h1>
                <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-2">Acesse seu perfil para agendar seu corte</p>
            </div>

            <div class="bg-[#121212] border border-zinc-800 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-[#D4AF37]"></div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

<<<<<<< HEAD
                    {{-- LOGIN: EMAIL OU WHATSAPP --}}
                    <div class="space-y-2">
                        <label for="login" class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">E-mail ou WhatsApp</label>
                        <x-text-input id="login" class="block w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#D4AF37] focus:ring-0" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" placeholder="email@exemplo.com ou (85) 90000-0000" />
                        <x-input-error :messages="$errors->get('login')" class="mt-2" />
=======
                    {{-- EMAIL --}}
                    <div class="space-y-2">
                        <label for="email" class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">E-mail</label>
                        <x-text-input id="email" class="block w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#D4AF37] focus:ring-0" type="email" name="email" :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                    </div>

                    {{-- PASSWORD --}}
                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-2">
                            <label for="password" class="text-[9px] font-black uppercase text-zinc-500 italic tracking-widest">Senha</label>
                            @if (Route::has('password.request'))
                                <a class="text-[9px] font-black uppercase text-[#D4AF37] hover:underline" href="{{ route('password.request') }}">Esqueceu?</a>
                            @endif
                        </div>
                        <x-text-input id="password" class="block w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#D4AF37] focus:ring-0" type="password" name="password" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- REMEMBER ME --}}
                    <div class="flex items-center px-2">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-zinc-800 bg-zinc-900 text-[#D4AF37] focus:ring-0 shadow-none" name="remember">
                            <span class="ms-2 text-[10px] font-black text-zinc-500 uppercase italic tracking-widest">Lembrar de mim</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-[#D4AF37] text-black py-4 rounded-2xl font-black uppercase text-[11px] tracking-[0.2em] shadow-lg active:scale-95 transition-all">
                        Fazer Login
                    </button>
                </form>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('register') }}" class="text-zinc-500 text-[10px] font-black uppercase tracking-[0.2em] hover:text-[#D4AF37] transition-colors">
                    Não tem conta? <span class="text-[#D4AF37] underline">Cadastre-se agora</span>
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
