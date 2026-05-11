<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barber Nathan | Estilo e Tradição</title>

    <!-- Fontes do Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@800&family=Work+Sans:wght@400;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        .text-outline {
            -webkit-text-stroke: 1px #FFFFFF;
            color: transparent;
        }
    </style>
</head>
<body class="font-work-sans antialiased text-zinc-900 bg-[#F8F5EF]">

<div class="relative w-full">
    <!-- NAVBAR -->
    <div class="absolute top-0 left-0 w-full z-[100]">
        @include('partials.navbar')
    </div>

    <main>
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

    </main>
</div>

</body>
</html>
