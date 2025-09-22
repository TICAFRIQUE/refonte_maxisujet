<!-- filepath: c:\laragon\www\refonte_maxisujet\resources\views\frontend\pages\user\emails\welcome_email.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue sur MaxiSujets !</title>
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
        <h2>Bienvenue sur MaxiSujets !</h2>
        <div class="info">
            Bonjour <strong>{{ $user->username ?? $user->email }}</strong>,
        </div>
        <p>Nous sommes ravis de vous compter parmi nos membres. MaxiSujets vous donne accès à des milliers de documents scolaires et universitaires pour réussir vos études.</p>
        <p>Commencez dès maintenant à explorer les sujets, télécharger des ressources ou partager vos propres documents avec la communauté.</p>
        <p style="text-align:center;">
            <a href="{{ route('sujet.front.index') }}" class="btn">Découvrir les sujets</a>
        </p>
        <p style="font-size:0.95rem;color:#555;">
            Besoin d’aide ou d’informations ? <br>
            Contactez-nous à <a href="mailto:info@maxisujets.net" style="color:#0d6efd;">info@maxisujets.net</a>
        </p>
        <p class="footer">
            &copy; {{ date('Y') }} MaxiSujets<br>
            Merci pour votre confiance !
        </p>
    </div>
</body>
</html>