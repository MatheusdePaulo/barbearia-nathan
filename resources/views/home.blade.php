<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barber Nathan | Catálogo de Serviços</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #050505; color: white; font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="flex min-h-screen">

<aside class="w-[320px] bg-[#121212] border-r border-zinc-800 p-8">
    <h1 class="text-[#D4AF37] text-2xl font-bold mb-10 text-center uppercase">BARBER NATHAN</h1>
    <nav class="space-y-4">
        <a href="{{ url('/') }}" class="block px-4 py-2 bg-zinc-800/50 rounded-lg text-[#D4AF37] font-medium border border-[#D4AF37]/20">Início</a>
        <a href="#" class="block px-4 py-2 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-lg transition-all">Meus Agendamentos</a>
    </nav>
</aside>

<main class="flex-1 p-12">
    <header class="mb-12">
        <h2 class="text-4xl font-bold italic">Escolha o seu <span class="text-[#D4AF37]">Serviço</span></h2>
        <p class="text-zinc-500 mt-2 text-lg">Cortes modernos e atendimento premium com Nathan do Corte.</p>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($services as $service)
            <div class="bg-[#121212] border border-zinc-800 p-6 rounded-2xl hover:border-[#D4AF37] transition-all group flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold group-hover:text-[#D4AF37] transition-colors">{{ $service->name }}</h3>
                        <span class="text-[#D4AF37] font-extrabold text-xl">R$ {{ number_format($service->price, 2, ',', '.') }}</span>
                    </div>
                    <p class="text-zinc-500 text-sm mb-6 line-clamp-2">{{ $service->description }}</p>
                </div>

                <div class="flex items-center justify-between gap-4 mt-auto">
                    <div class="flex items-center text-zinc-400 text-xs uppercase tracking-wider">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $service->duration }} min
                    </div>

                    <a href="{{ route('appointments.create', $service->id) }}"
                       class="flex-1 py-3 bg-[#D4AF37] text-black text-center font-black uppercase text-xs rounded-lg group-hover:bg-[#f3ca4a] transition-colors shadow-lg shadow-[#D4AF37]/5">
                        Agendar agora
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-2 border-2 border-dashed border-zinc-800 rounded-3xl p-20 text-center">
                <h3 class="text-xl font-bold text-zinc-400">Nenhum serviço disponível no momento</h3>
            </div>
        @endforelse
    </div>
</main>

</body>
</html>
