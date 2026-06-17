<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title, ENT_QUOTES, 'UTF-8') : 'Erreur' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap');
        body {
            font-family: 'Outfit', sans-serif;
            background: #F8FAFC;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-code {
            font-size: 80px;
            font-weight: 800;
            color: #2563EB;
            line-height: 1;
        }
        .error-card {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            padding: 48px 40px;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 30px rgba(15,23,42,.07);
        }
        .btn-home {
            background: #2563EB;
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: background .2s;
        }
        .btn-home:hover { background: #1D4ED8; color: #fff; }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-code"><?= http_response_code() ?></div>
        <h3 class="fw-bold mt-3 mb-2" style="color:#0F172A">Oups ! Une erreur est survenue</h3>
        <p class="text-muted mb-4">
            <?= isset($error_message) ? htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') : "La page demandée est introuvable ou a été déplacée." ?>
        </p>
        <a href="<?= function_exists('base_url') ? base_url('/') : '/' ?>" class="btn-home">
            <i class="bi bi-house me-1"></i> Retour à l'accueil
        </a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
