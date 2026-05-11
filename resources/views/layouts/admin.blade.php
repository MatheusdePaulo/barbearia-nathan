<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Barber Nathan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background-color: #050505; color: white; font-family: 'Inter', sans-serif; }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #D4AF37; border-radius: 10px; }

        /* Controle de visibilidade via JS para o mobile */
        #sidebar.active { transform: translateX(0); }
        #sidebar-overlay.active { display: block; }
    </style>
</head>
<body class="flex h-screen bg-[#050505] overflow-hidden">

{{-- Variáveis de Estilo --}}
@php
    $activeClass = 'bg-[#D4AF37]/10 text-[#D4AF37] border-[#D4AF37]/20 font-black italic shadow-[inset_0_0_10px_rgba(212,175,55,0.05)]';
    $defaultClass = 'text-zinc-400 hover:bg-zinc-800 hover:text-white border-transparent';
@endphp

<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/80 z-40 hidden lg:hidden transition-all duration-300"></div>

<aside id="sidebar" class="fixed inset-y-0 left-0 w-72 bg-[#121212] border-r border-zinc-800 flex flex-col shrink-0 z-50
    -translate-x-full lg:translate-x-0 lg:static transition-transform duration-300 ease-in-out">

    <div class="p-8 flex justify-center items-center border-b border-zinc-800/50 h-[150px] relative">
        <img src="{{ asset('images/logotipo_nathan.png') }}"
             alt="Barber Nathan"
             class="max-h-[100px] w-auto object-contain">

        <button onclick="toggleSidebar()" class="absolute top-4 right-4 text-zinc-500 lg:hidden p-2">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <nav class="flex-1 px-6 py-6 space-y-1 overflow-y-auto custom-scroll">
        <p class="text-[10px] uppercase font-black text-zinc-500 tracking-[0.2em] mb-4 ml-4">Gerenciamento</p>

        {{-- PAINEL --}}
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl border font-bold uppercase text-[11px] transition-all {{ Route::is('admin.dashboard') ? $activeClass : $defaultClass }}">
            <i class="fas fa-th-large w-5"></i> Painel Administrativo
        </a>

        {{-- AGENDA --}}
        <a href="{{ route('admin.agenda') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl border font-bold uppercase text-[11px] transition-all {{ Route::is('admin.agenda') ? $activeClass : $defaultClass }}">
            <i class="fas fa-calendar-alt w-5"></i> Agenda
        </a>

        {{-- CLIENTES --}}
        <a href="{{ route('admin.customers') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl border font-bold uppercase text-[11px] transition-all {{ Route::is('admin.customers') ? $activeClass : $defaultClass }}">
            <i class="fas fa-users w-5"></i> Clientes
        </a>

        {{-- SERVIÇOS --}}
        <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl border font-bold uppercase text-[11px] transition-all {{ Route::is('admin.services.*') ? $activeClass : $defaultClass }}">
            <i class="fas fa-cut w-5"></i> Serviços
        </a>

        {{-- PRODUTOS --}}
        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl border font-bold uppercase text-[11px] transition-all {{ Route::is('admin.products.*') ? $activeClass : $defaultClass }}">
            <i class="fas fa-shopping-bag w-5"></i> Produtos
        </a>

        {{-- RELATÓRIO --}}
        <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl border font-bold uppercase text-[11px] transition-all {{ Route::is('admin.reports') ? $activeClass : $defaultClass }}">
            <i class="fas fa-chart-line w-5"></i> Relatório
        </a>

        <div class="pt-6">
            <p class="text-[10px] uppercase font-black text-[#D4AF37] tracking-[0.2em] mb-4 ml-4">Marketing</p>
            <a href="{{ route('admin.birthdays') }}" class="flex items-center justify-between px-4 py-3 rounded-xl border transition-all {{ Route::is('admin.birthdays') ? $activeClass : $defaultClass }}">
                <div class="flex items-center gap-3 font-bold uppercase text-[11px]">
                    <i class="fas fa-birthday-cake w-5"></i> Aniversariantes
                </div>
                @if(isset($aniversariantesHoje) && $aniversariantesHoje > 0)
                    <span class="bg-[#D4AF37] text-black text-[10px] font-black px-2 py-0.5 rounded-full shadow-[0_0_8px_rgba(212,175,55,0.4)]">
                        {{ $aniversariantesHoje }}
                    </span>
                @endif
            </a>
        </div>
    </nav>

    {{-- BASE DO MENU: CONFIGURAÇÕES E SAIR --}}
    <div class="mt-auto pt-6 border-t border-zinc-800/50">
        {{-- CONFIGURAÇÕES --}}
        <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-10 py-4 font-bold uppercase text-[11px] transition-all {{ Route::is('admin.settings') ? 'text-[#D4AF37] italic' : 'text-zinc-500 hover:text-white' }}">
            <i class="fas fa-cog w-5"></i> Configurações
        </a>

        {{-- FORMULÁRIO DE LOGOUT --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

        <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="flex items-center gap-4 px-10 py-5 w-full text-left group transition-all">
            <div class="w-8 h-8 rounded-lg bg-red-600/10 flex items-center justify-center text-red-600 border border-red-600/20 group-hover:bg-red-600 group-hover:text-white transition-all">
                <i class="fas fa-sign-out-alt text-xs"></i>
            </div>
            <span class="text-[10px] font-black uppercase italic tracking-widest text-red-600 group-hover:text-red-500">Sair da Conta</span>
        </button>
    </div>
</aside>

<main class="flex-1 flex flex-col min-w-0 bg-[#050505] relative overflow-hidden">

    <header class="lg:hidden h-16 bg-[#0A0A0A] border-b border-zinc-800 flex items-center justify-between px-6 shrink-0 z-30">
        <button onclick="toggleSidebar()" class="text-[#D4AF37] p-2 -ml-2">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <span class="text-[10px] font-black uppercase text-white tracking-widest italic">Barber Nathan</span>
        <div class="w-8 h-8 rounded-lg bg-zinc-900 border border-zinc-800 overflow-hidden">
            <img src="https://ui-avatars.com/api/?name=Matheus+de+Paulo&background=D4AF37&color=000&bold=true" class="w-full h-full">
        </div>
    </header>

    <div class="flex-1 overflow-y-auto custom-scroll">
        @yield('content')
    </div>
</main>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>
</body>
</html>
