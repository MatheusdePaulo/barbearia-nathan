@extends('layouts.admin')

@section('content')
    <div class="p-4 sm:p-8 space-y-8 bg-[#050505] min-h-screen">

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

                    {{-- Definido os names unit_name e unit_whatsapp para o Controller --}}
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase text-zinc-500 ml-2">Nome da Barbearia</label>
                            <input type="text" name="unit_name" value="{{ $barbearia['nome'] }}"
                                   class="w-full bg-zinc-900 border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase text-zinc-500 ml-2">WhatsApp de Contato</label>
                            <input type="text" name="unit_whatsapp" value="{{ $barbearia['whatsapp'] }}"
                                   class="w-full bg-zinc-900 border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none transition-all">
                        </div>
                        <div class="sm:col-span-2 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto bg-[#D4AF37] text-black px-8 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg active:scale-95 transition-all">Salvar Unidade</button>
                        </div>
                    </form>
                </div>

                {{-- SEGURANÇA --}}
                <div class="bg-[#121212] p-6 sm:p-8 rounded-[2.5rem] border border-zinc-800 shadow-2xl">
                    <h4 class="text-white font-black italic uppercase text-xs tracking-widest mb-8 flex items-center gap-3">
                        <i class="fas fa-shield-alt text-[#D4AF37]"></i> Segurança e Senha
                    </h4>

                    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase text-zinc-500 ml-2">Nova Senha</label>
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
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
