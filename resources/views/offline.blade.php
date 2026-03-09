<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hors ligne - {{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: system-ui, -apple-system, sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #003366 0%, #001122 100%);
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .box {
            max-width: 400px;
        }
        h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
        p { opacity: 0.9; margin-bottom: 1.5rem; }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #FFD700;
            color: #1a1a1a;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>Vous êtes hors ligne</h1>
        <p>La connexion est interrompue. Réessayez lorsque l’internet sera de nouveau disponible. Les données seront synchronisées automatiquement.</p>
        <a href="{{ url('/') }}" class="btn">Réessayer</a>
    </div>
</body>
</html>
