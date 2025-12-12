<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des joueurs</title>
</head>
<body>
<?php $joueurs = $_SESSION['joueurs'] ?? []; ?>
<h2>Liste des joueurs</h2>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Poste</th>
    </tr>

    <?php foreach ($joueurs as $j) : ?>
        <tr>
            <td><?= htmlspecialchars($j['id']); ?></td>
            <td><?= htmlspecialchars($j['nom']); ?></td>
            <td><?= htmlspecialchars($j['prenom']); ?></td>
            <td><?= htmlspecialchars($j['poste']); ?></td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
