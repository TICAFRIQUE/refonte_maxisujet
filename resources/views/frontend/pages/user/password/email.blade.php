<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation de mot de passe - MaxiSujets</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f6f8fa;
            margin: 0;
            padding: 0;
        }
        .mail-container {
            max-width: 480px;
            margin: 30px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            padding: 32px 24px;
        }
        .logo {
            display: block;
            margin: 0 auto 18px auto;
            height: 48px;
        }
        h2 {
            color: #0d6efd;
            font-size: 1.3rem;
            margin-bottom: 12px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            background: #0d6efd;
            color: #fff !important;
            padding: 12px 32px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            font-size: 1rem;
            margin: 18px 0;
            box-shadow: 0 2px 6px rgba(13,110,253,0.08);
            transition: background 0.2s;
        }
        .btn:hover {
            background: #0b5ed7;
        }
        .footer {
            font-size: 0.9rem;
            color: #888;
            text-align: center;
            margin-top: 24px;
        }
        .info {
            background: #e7f1ff;
            border-left: 4px solid #0d6efd;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 18px;
            color: #222;
        }
    </style>
</head>
<body>
    <div class="mail-container">
        <img src="{{ asset('frontend/img/logo.png') }}" alt="MaxiSujets" class="logo">
        <h2>Réinitialisation de votre mot de passe</h2>
        <div class="info">
            Bonjour <strong>{{ $user->username ?? $user->email }}</strong>,
        </div>
        <p>Vous avez demandé la réinitialisation de votre mot de passe sur <strong>MaxiSujets</strong>.</p>
        <p>Pour choisir un nouveau mot de passe, cliquez sur le bouton ci-dessous :</p>
        <p style="text-align:center;">
            <a href="{{ $resetLink }}" class="btn">Réinitialiser mon mot de passe</a>
        </p>
        <p style="font-size:0.95rem;color:#555;">
            Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :<br>
            <a href="{{ $resetLink }}" style="color:#0d6efd;word-break:break-all;">{{ $resetLink }}</a>
        </p>
        <p class="footer">
            Si vous n'avez pas demandé cette opération, ignorez ce message.<br>
            &copy; {{ date('Y') }} MaxiSujets
        </p>
    </div>
</body>
</html>