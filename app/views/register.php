<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>

    <link rel="stylesheet" href="../../assets/css/stylee.css">
</head>

<body>

<div class="container">

    <form action="../controllers/RegisterController.php" method="POST" class="form-box">

        <h2>Inscription</h2>

        <input type="text" name="nom" placeholder="Nom" required>

        <input type="text" name="prenom" placeholder="Prénom" required>

        <input type="email" name="email" placeholder="Email" required>

        <input type="text" name="telephone" placeholder="Téléphone">

        <input type="password" name="password" placeholder="Mot de passe" required>

        <button type="submit">S'inscrire</button>

        <p>
            Déjà un compte ?
            <a href="login.php">Se connecter</a>
        </p>

    </form>

</div>

</body>
</html>