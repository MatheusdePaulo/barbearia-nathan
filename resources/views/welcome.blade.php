<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>Barber Nathan | Barbearia Premium em Cascavel, CE</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">

    {{-- SEO básico --}}
    <meta name="description" content="Barber Nathan — barbearia premium com agendamento online. Cortes, barba, combos e muito mais. Agende agora e garanta seu horário.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">

    {{-- Open Graph (WhatsApp, Instagram, Facebook) --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Barber Nathan | Barbearia Premium">
    <meta property="og:description" content="Agendamento online, cortes modernos, barba esculpida e combos exclusivos. Reserve o seu horário.">
    <meta property="og:image" content="{{ asset('images/hero-bg.webp') }}">
    <meta property="og:locale" content="pt_BR">

    {{-- Schema.org LocalBusiness --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "HairSalon",
        "name": "Barber Nathan",
        "description": "Barbearia premium com agendamento online.",
        "url": "{{ url('/') }}",
        "image": "{{ asset('images/hero-bg.webp') }}",
        "priceRange": "$$",
        "openingHoursSpecification": [
            { "@@type": "OpeningHoursSpecification", "dayOfWeek": ["Tuesday","Wednesday","Thursday","Friday"], "opens": "08:30", "closes": "19:00" },
            { "@@type": "OpeningHoursSpecification", "dayOfWeek": ["Saturday"], "opens": "07:30", "closes": "17:00" }
        ]
    }
    </script>

    {{-- 1. PRÉ-CONEXÃO E CARREGAMENTO DE FONTES OTIMIZADO --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    {{-- Preload da fonte crítica para evitar o aviso de "Exibição de fontes" --}}
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Barlow:wght@800&family=Work+Sans:wght@400;700&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow:wght@800&family=Work+Sans:wght@400;700&display=swap" media="print" onload="this.media='all'" />

    {{-- 2. FONT AWESOME: Carregamento não bloqueante corrigido --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" 
          media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </noscript>

    {{-- 3. VITE: CSS e JS compilados --}}
=======
    <title>Barber Nathan | Estilo e Tradição</title>

    <!-- Fontes do Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@800&family=Work+Sans:wght@400;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
<<<<<<< HEAD
        {{-- Estilo crítico inline para evitar saltos de layout (CLS) --}}
        .text-outline { -webkit-text-stroke: 1px #FFFFFF; color: transparent; }
        
        {{-- Força o background inicial para evitar tela branca antes do Hero --}}
        body { background-color: #F8F5EF; }
=======
        .text-outline {
            -webkit-text-stroke: 1px #FFFFFF;
            color: transparent;
        }
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
    </style>
</head>
<body class="font-work-sans antialiased text-zinc-900 bg-[#F8F5EF]">

<div class="relative w-full">
<<<<<<< HEAD
    <!-- NAVBAR: Z-index alto para sobrepor o Hero -->
=======
    <!-- NAVBAR -->
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
    <div class="absolute top-0 left-0 w-full z-[100]">
        @include('partials.navbar')
    </div>

    <main>
<<<<<<< HEAD
        {{-- O Hero é o LCP (Maior pintura com conteúdo), as mudanças no partial já devem estar ativas --}}
        @include('partials.hero')

        @include('partials.professional')

        @include('partials.services')

        @include('partials.cta')

        {{-- Importante: No partial de produtos, garanta que as imagens sejam .webp e usem loading="lazy" --}}
        @include('partials.products')

        @include('partials.why-choose-us')

        @include('partials.footer')
=======
        <!-- HERO -->
        @include('partials.hero')

        <!-- PROFISSIONAL -->
        @include('partials.professional')

        <!-- SERVIÇOS -->
        @include('partials.services')

        <!-- CTA -->
        @include('partials.cta')

        <!-- PRODUCTS -->
        @include('partials.products')

        <!-- PORQUE NOS ESCOLHER -->
        @include('partials.why-choose-us')

        <!-- O Footer entra por último, contendo o agendamento e o mapa -->
        @include('partials.footer')

>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
    </main>
</div>

</body>
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
