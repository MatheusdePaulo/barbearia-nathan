<x-guest-layout>
    <div class="min-h-screen bg-[#050505] flex items-center justify-center p-4 py-10">
        <div class="w-full max-w-md">

            <div class="text-center mb-8">
                <a href="/" class="inline-block mb-4">
                    <img src="{{ asset('images/logotipo_nathan.png') }}" alt="Barber Nathan" class="h-16 w-auto mx-auto">
                </a>
                <h1 class="text-3xl font-black italic text-white uppercase tracking-tighter">Criar Perfil</h1>
                <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-2 px-4 leading-relaxed">
                    O seu estilo começa com um bom cadastro
                </p>
            </div>

            <div class="bg-[#121212] border border-zinc-800 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-[#D4AF37]"></div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data>
                    @csrf

                    {{-- SEÇÃO: INFORMAÇÕES PESSOAIS --}}
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black uppercase text-[#D4AF37] ml-2 italic tracking-widest">Nome Completo</label>
                            <x-text-input id="name" class="block w-full bg-zinc-900/50 border-zinc-800 rounded-2xl py-3.5 px-5 text-white text-sm focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37]/20 transition-all" type="text" name="name" :value="old('name')" required autofocus placeholder="Ex: Matheus de Paulo" />
                            <x-input-error :messages="$errors->get('name')" class="mt-1 ml-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- WHATSAPP --}}
                            <div class="space-y-1">
                                <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic">WhatsApp</label>
                                <x-text-input id="phone" class="block w-full bg-zinc-900/50 border-zinc-800 rounded-2xl py-3.5 px-5 text-white text-sm focus:border-[#D4AF37] focus:ring-0" type="text" name="phone" placeholder="(85) 90000-0000" required x-mask="(99) 99999-9999" />
                            </div>

                            {{-- NASCIMENTO --}}
                            <div class="space-y-1">
                                <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic">Nascimento</label>
                                <x-text-input id="birthday" class="block w-full bg-zinc-900/50 border-zinc-800 rounded-2xl py-3.5 px-5 text-white text-sm focus:border-[#D4AF37] focus:ring-0" type="date" name="birthday" required style="color-scheme: dark;" />
                            </div>
                        </div>
                    </div>

                    <div class="h-px bg-zinc-800/50 w-full my-2"></div>

                    {{-- SEÇÃO: ACESSO --}}
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic">E-mail de Acesso</label>
                            <x-text-input id="email" class="block w-full bg-zinc-900/50 border-zinc-800 rounded-2xl py-3.5 px-5 text-white text-sm focus:border-[#D4AF37] focus:ring-0" type="email" name="email" :value="old('email')" required placeholder="email@exemplo.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-1 ml-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic">Senha</label>
                                <x-text-input id="password" class="block w-full bg-zinc-900/50 border-zinc-800 rounded-2xl py-3.5 px-5 text-white text-sm focus:border-[#D4AF37] focus:ring-0" type="password" name="password" required placeholder="••••••••" />
                            </div>
                            <div class="space-y-1">
                                <label class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic">Confirmar</label>
                                <x-text-input id="password_confirmation" class="block w-full bg-zinc-900/50 border-zinc-800 rounded-2xl py-3.5 px-5 text-white text-sm focus:border-[#D4AF37] focus:ring-0" type="password" name="password_confirmation" required placeholder="••••••••" />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="col-span-2 mt-1 ml-2" />
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#D4AF37] hover:bg-[#b8962d] text-black py-4 rounded-2xl font-black uppercase text-[11px] tracking-[0.2em] shadow-[0_10px_20px_rgba(212,175,55,0.2)] active:scale-[0.98] transition-all mt-6">
                        Concluir Cadastro
                    </button>

                    <p class="text-center text-zinc-600 text-[10px] font-bold uppercase tracking-widest mt-4">
                        Já tem conta? <a href="{{ route('login') }}" class="text-[#D4AF37] hover:underline">Fazer Login</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
