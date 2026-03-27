<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LoCar - Bem-vindo</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .hero-content {
            text-align: center;
            max-width: 600px;
            padding: 20px;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .hero-content p {
            font-size: 1.25rem;
            margin-bottom: 30px;
            opacity: 0.95;
            line-height: 1.6;
        }

        .cta-button {
            display: inline-block;
            padding: 15px 50px;
            background-color: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            color: #764ba2;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 60px;
            margin-left: auto;
            margin-right: auto;
            max-width: 1000px;
            padding: 0 20px;
        }

        .feature-item {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .feature-item h3 {
            font-size: 1.2rem;
            margin: 0 0 10px 0;
        }

        .feature-item p {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            .cta-button {
                padding: 12px 40px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <section class="hero">
        <div class="hero-content">
            <h1>🚗 LoCar</h1>
            <p>Seu novo meio de alugar carros</p>
            <p>Alugue o seu carro perfeito com segurança, comodidade e os melhores preços do mercado.</p>

            <a href="{{ route('login') }}" class="cta-button">Fazer Login</a>

            <div class="features">
                <div class="feature-item">
                    <h3>✨ Fácil</h3>
                    <p>Alugue em poucos cliques</p>
                </div>
                <div class="feature-item">
                    <h3>💰 Preços</h3>
                    <p>Os melhores valores garantidos</p>
                </div>
                <div class="feature-item">
                    <h3>🛡️ Seguro</h3>
                    <p>Frotas bem mantidas e seguras</p>
                </div>
                <div class="feature-item">
                    <h3>📍 Localização</h3>
                    <p>Sempre perto de você</p>
                </div>
            </div>
        </div>
    </section>
</body>
</html>

