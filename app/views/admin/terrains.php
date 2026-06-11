<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Terrains</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f5f5f5;
            margin:0;
            padding:20px;
        }

        .container{
            max-width:1200px;
            margin:auto;
            background:#fff;
            padding:20px;
            border-radius:10px;
            box-shadow:0 2px 10px rgba(0,0,0,.1);
        }

        h1{
            text-align:center;
            margin-bottom:20px;
        }

        form{
            display:grid;
            gap:10px;
            margin-bottom:30px;
        }

        input, button{
            padding:10px;
        }

        button{
            background:#007bff;
            color:white;
            border:none;
            cursor:pointer;
        }

        button:hover{
            background:#0056b3;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th,
        table td{
            border:1px solid #ddd;
            padding:10px;
            text-align:center;
        }

        table th{
            background:#007bff;
            color:white;
        }

        img{
            border-radius:5px;
        }

        .delete-btn{
            color:red;
            text-decoration:none;
            font-weight:bold;
        }

        .delete-btn:hover{
            text-decoration:underline;
        }
    </style>
</head>
<body>

<div class="container">

    <h1>Gestion des Terrains</h1>

    <form method="POST" action="../../controllers/TerrainController.php" enctype="multipart/form-data">

        <input
            type="text"
            name="nom"
            placeholder="Nom du terrain"
            required
        >

        <input
            type="text"
            name="emplacement"
            placeholder="Emplacement"
        >

        <input
            type="number"
            step="0.01"
            name="prix"
            placeholder="Prix / heure"
            required
        >

        <input
            type="text"
            name="localisation"
            placeholder="Ville ou lien Google Maps"
        >

        <input
            type="file"
            name="image"
            accept=".jpg,.jpeg,.png,.webp"
            required
        >

        <button type="submit" name="add">
            Ajouter
        </button>

    </form>

    <table>

        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Nom</th>
                <th>Emplacement</th>
                <th>Localisation</th>
                <th>Prix</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        <?php if (!empty($terrains)): ?>

            <?php foreach ($terrains as $t): ?>

                <tr>

                    <td><?= (int)$t['id']; ?></td>

                    <td>
                        <?php if (!empty($t['image'])): ?>
                            <img src="/riiiiiida/uploads/<?= htmlspecialchars($t['image']); ?>" width="100" alt="image">
                        <?php else: ?>
                            Aucune image
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($t['nom']); ?></td>

                    <td><?= htmlspecialchars($t['emplacement']); ?></td>

                    <td><?= htmlspecialchars($t['localisation']); ?></td>

                    <td>
                        <?= number_format((float)$t['prix'], 2); ?> DH
                    </td>

                    <td>

                        <a
                            class="delete-btn"
                            href="?delete=<?= (int)$t['id']; ?>"
                            onclick="return confirm('Voulez-vous vraiment supprimer ce terrain ?');"
                        >
                            Supprimer
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="7">
                    Aucun terrain trouvé
                </td>
            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>

</body>
</html>