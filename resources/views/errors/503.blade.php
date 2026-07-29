<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nathan do Corte | Site Offline</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: #050505;
            color: white;
            font-family: 'Arial Black', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            text-align: center;
            max-width: 800px;
            width: 100%;
        }

        .logo {
            width: 180px;
            height: auto;
            margin: 0 auto 40px;
            display: block;
            opacity: 0.9;
        }

        .divider {
            width: 80px;
            height: 3px;
            background-color: #D4AF37;
            margin: 0 auto 40px;
        }

        .icon {
            font-size: 64px;
            color: #D4AF37;
            margin-bottom: 32px;
            display: block;
        }

        .title {
            font-size: clamp(32px, 6vw, 64px);
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -0.02em;
            line-height: 1;
            color: #ffffff;
            margin-bottom: 24px;
            font-style: italic;
        }

        .message {
            font-size: clamp(16px, 3vw, 28px);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #D4AF37;
            line-height: 1.4;
            margin-bottom: 40px;
        }

        .sub-message {
            font-size: clamp(13px, 2vw, 18px);
            color: #71717a;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .whatsapp-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 40px;
            padding: 16px 32px;
            background-color: #25D366;
            color: #000;
            font-weight: 900;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            border-radius: 50px;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .whatsapp-btn:hover {
            opacity: 0.85;
        }

        .footer {
            margin-top: 60px;
            font-size: 11px;
            color: #3f3f46;
            text-transform: uppercase;
            letter-spacing: 0.2em;
        }
    </style>
</head>
<body>
<div class="container">

    <img src="/images/logotipo_nathan.webp" alt="Nathan do Corte" class="logo"
         onerror="this.style.display='none'">

    <div class="divider"></div>

    <i class="fas fa-store-slash icon"></i>

    <h1 class="title">Site Offline</h1>

    <p class="message">
        Entre em contato com o<br>
        Administrador — Nathan do Corte
    </p>

    <p class="sub-message">Voltamos em breve ✂️</p>

    <a href="https://wa.me/5585986839615" class="whatsapp-btn" target="_blank">
        <i class="fab fa-whatsapp" style="font-size: 20px;"></i>
        Falar com Nathan
    </a>

    <p class="footer">© Nathan do Corte — Todos os direitos reservados</p>

</div>
</body>
</html>
