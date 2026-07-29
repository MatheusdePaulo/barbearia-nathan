@extends('layouts.admin')

@section('content')
    <div class="p-4 sm:p-8 space-y-8 bg-[#050505] min-h-screen">

<<<<<<< HEAD
        {{-- ALERTAS DE SUCESSO OU ERRO GERAL --}}
        @if(session('success'))
            <div class="bg-green-500/20 border-b border-green-500 text-green-500 px-6 py-3 text-xs font-black uppercase italic tracking-widest animate-pulse rounded-xl">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500/20 border-b border-red-500 text-red-500 px-6 py-3 text-xs font-black uppercase italic tracking-widest rounded-xl">
                <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            </div>
        @endif

=======
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
        {{-- HEADER --}}
        <div class="text-center lg:text-left">
            <h1 class="text-xl sm:text-2xl font-black italic text-white uppercase tracking-tighter">Configurações do Sistema</h1>
            <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-1">Gerencie seu perfil e as regras da barbearia</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- COLUNA 1: PERFIL --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-[#121212] p-8 rounded-[2.5rem] border border-zinc-800 shadow-2xl text-center relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-1 bg-[#D4AF37]"></div>

                    <div class="relative inline-block mb-6">
                        {{-- Avatar dinâmico puxando o nome do Admin logado --}}
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=D4AF37&color=000&bold=true"
                             class="w-24 h-24 rounded-3xl border-2 border-zinc-800 shadow-2xl mx-auto">
                        <button class="absolute -bottom-2 -right-2 bg-zinc-900 text-[#D4AF37] p-2 rounded-xl border border-zinc-800 hover:bg-[#D4AF37] hover:text-black transition-all">
                            <i class="fas fa-camera text-xs"></i>
                        </button>
                    </div>

                    <h3 class="text-white font-black italic uppercase text-lg leading-none">{{ auth()->user()->name }}</h3>
                    <p class="text-[#D4AF37] text-[10px] font-bold uppercase tracking-[0.2em] mt-2">Administrador Master</p>

                    <div class="mt-8 pt-8 border-t border-zinc-800/50 space-y-4">
                        <div class="text-left">
                            <label class="text-[9px] font-black uppercase text-zinc-600 block mb-1">E-mail de Acesso</label>
                            <p class="text-zinc-400 text-xs font-mono">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>

                {{-- BOTÃO SAIR --}}
                <div class="lg:hidden">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-red-600/10 border border-red-600/20 text-red-600 py-4 rounded-2xl font-black uppercase text-[10px] tracking-[.3em] italic flex items-center justify-center gap-3 active:scale-95 transition-all">
                            <i class="fas fa-sign-out-alt"></i> Sair da Conta
                        </button>
                    </form>
                </div>
            </div>

            {{-- COLUNA 2 e 3: FORMULÁRIOS --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- DADOS DA BARBEARIA --}}
                <div class="bg-[#121212] p-6 sm:p-8 rounded-[2.5rem] border border-zinc-800 shadow-2xl">
                    <h4 class="text-white font-black italic uppercase text-xs tracking-widest mb-8 flex items-center gap-3">
                        <i class="fas fa-store text-[#D4AF37]"></i> Dados da Unidade
                    </h4>

<<<<<<< HEAD
=======
                    {{-- Definido os names unit_name e unit_whatsapp para o Controller --}}
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase text-zinc-500 ml-2">Nome da Barbearia</label>
<<<<<<< HEAD
                            <input type="text" name="unit_name" value="{{ $barbearia['nome'] }}" required
                                   class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase text-zinc-500 ml-2">WhatsApp de Contato</label>
                            <input type="text" name="unit_whatsapp" value="{{ $barbearia['whatsapp'] }}" required
                                   class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none transition-all">
=======
                            <input type="text" name="unit_name" value="{{ $barbearia['nome'] }}"
                                   class="w-full bg-zinc-900 border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase text-zinc-500 ml-2">WhatsApp de Contato</label>
                            <input type="text" name="unit_whatsapp" value="{{ $barbearia['whatsapp'] }}"
                                   class="w-full bg-zinc-900 border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none transition-all">
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                        </div>
                        <div class="sm:col-span-2 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto bg-[#D4AF37] text-black px-8 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg active:scale-95 transition-all">Salvar Unidade</button>
                        </div>
                    </form>
                </div>

<<<<<<< HEAD
                {{-- CUPOM DE AVALIAÇÃO GOOGLE --}}
                <div class="bg-[#121212] p-6 sm:p-8 rounded-[2.5rem] border border-zinc-800 shadow-2xl">
                    <h4 class="text-white font-black italic uppercase text-xs tracking-widest mb-8 flex items-center gap-3">
                        <i class="fab fa-google text-[#D4AF37]"></i> Cupom de Avaliação Google
                    </h4>

                    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase text-zinc-500 ml-2">Status do Cupom</label>
                                <div class="flex items-center gap-3 bg-zinc-900 border border-zinc-800 rounded-2xl px-4 py-3">
                                    <input type="hidden" name="review_coupon_active" value="0">
                                    <input type="checkbox" name="review_coupon_active" value="1" id="review_coupon_active"
                                           {{ $reviewCoupon['active'] ? 'checked' : '' }}
                                           class="w-4 h-4 accent-[#D4AF37] cursor-pointer">
                                    <label for="review_coupon_active" class="text-sm text-zinc-300 cursor-pointer select-none">
                                        Oferecer cupom após avaliação no Google
                                    </label>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase text-zinc-500 ml-2">Desconto (%)</label>
                                <input type="number" name="review_coupon_percent"
                                       min="1" max="100" value="{{ $reviewCoupon['percent'] }}"
                                       class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none transition-all">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="w-full sm:w-auto bg-[#D4AF37] text-black px-8 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg active:scale-95 transition-all">Salvar Cupom</button>
                        </div>
                    </form>
                </div>

                {{-- SEGURANÇA E ALTERAÇÃO DE SENHA --}}
=======
                {{-- SEGURANÇA --}}
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                <div class="bg-[#121212] p-6 sm:p-8 rounded-[2.5rem] border border-zinc-800 shadow-2xl">
                    <h4 class="text-white font-black italic uppercase text-xs tracking-widest mb-8 flex items-center gap-3">
                        <i class="fas fa-shield-alt text-[#D4AF37]"></i> Segurança e Senha
                    </h4>

                    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase text-zinc-500 ml-2">Nova Senha</label>
<<<<<<< HEAD
                                <input type="password" name="password" placeholder="••••••••" required
                                       class="w-full bg-zinc-900 border rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none transition-all @error('password') border-red-500 @else border-zinc-800 @enderror">
                                @error('password') 
                                    <span class="text-[9px] text-red-500 uppercase font-black ml-2 block mt-1"><i class="fas fa-times-circle mr-1"></i> {{ $message }}</span> 
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase text-zinc-500 ml-2">Confirmar Senha</label>
                                <input type="password" name="password_confirmation" placeholder="••••••••" required
                                       class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none transition-all">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="w-full sm:w-auto bg-[#D4AF37] text-black px-8 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-opacity-90 shadow-lg active:scale-95 transition-all">Atualizar Senha</button>
=======
                                <input type="password" name="password" placeholder="••••••••"
                                       class="w-full bg-zinc-900 border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none @error('password') border-red-500 @enderror">
                                @error('password') <span class="text-[8px] text-red-500 uppercase font-black ml-2">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase text-zinc-500 ml-2">Confirmar Senha</label>
                                <input type="password" name="password_confirmation" placeholder="••••••••"
                                       class="w-full bg-zinc-900 border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="w-full sm:w-auto bg-zinc-800 text-white px-8 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-zinc-700 transition-all">Atualizar Senha</button>
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
<<<<<<< HEAD
@endsection
=======
@endsection
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
