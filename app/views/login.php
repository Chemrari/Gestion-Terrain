<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Connexion</title>

    <link rel="stylesheet" href="../../assets/css/stylee.css">

</head>

<body>

<div class="container">

    <form action="../controllers/LoginController.php" method="POST" class="form-box">

        <h2>Connexion</h2>

        <input
            type="email"
            name="email"
            placeholder="Email"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Mot de passe"
            required
        >

        <button type="submit">
            Se connecter
        </button>

        <p>
            Pas de compte ?
            <a href="register.php">
                S'inscrire
            </a>
        </p>

    </form>

</div>

</body>
</html>