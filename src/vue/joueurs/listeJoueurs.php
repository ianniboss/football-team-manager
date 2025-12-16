<?php
session_start();
require_once __DIR__ . '/../header.php';
?>

<?php $joueurs = $_SESSION['joueurs'] ?? []; ?>
<h2>Liste des joueurs</h2>
<a href="../../controleur/joueur/AjouterJoueur.php" class="btn">Ajouter un joueur</a>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>N° Licence</th>
        <th>Statut</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($joueurs as $j): ?>
        <tr>
            <td><?= htmlspecialchars($j['id_joueur']); ?></td>
            <td><?= htmlspecialchars($j['nom']); ?></td>
            <td><?= htmlspecialchars($j['prenom']); ?></td>
            <td><?= htmlspecialchars($j['num_licence']); ?></td>
            <td><?= htmlspecialchars($j['statut']); ?></td>
            <td>
                <a href="../../controleur/joueur/ObtenirUnJoueur.php?id=<?= $j['id_joueur']; ?>">Voir</a>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

</div>
</body>

</html>