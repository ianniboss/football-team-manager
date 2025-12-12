<?php require_once __DIR__ . '/../header.php'; ?>
<!-- utilise ObtenirToutesLesRencontres.php -->
<h2>Calendrier des Rencontres</h2>
<a href="ajouterRencontre.php" class="btn">Ajouter un match</a>

<table border="1">
    <thead>
        <tr>
            <th>Date</th>
            <th>Adversaire</th>
            <th>Lieu</th>
            <th>Résultat</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rencontres as $r): ?>
            <tr>
                <td><?php echo htmlspecialchars($r['date_rencontre']) . ' à ' . htmlspecialchars($r['heure']); ?></td>
                <td><?php echo htmlspecialchars($r['nom_equipe_adverse']); ?></td>
                <td><?php echo htmlspecialchars($r['lieu']); ?></td>
                <td><?php echo $r['resultat'] ? htmlspecialchars($r['resultat']) : 'À venir'; ?></td>
                <td>
                    <a href="RechercherUneRencontre.php?id=<?php echo $r['id_rencontre']; ?>">Détails</a>
                    <?php if (!$r['resultat']): ?>
                        | <a href="../selection/AfficherSelection.php?id_rencontre=<?php echo $r['id_rencontre']; ?>">Feuille de
                            match</a>
                    <?php endif; ?>
                    | <a href="SupprimerUneRencontre.php?id=<?php echo $r['id_rencontre']; ?>"
                        onclick="return confirm('Confirmer ?');">X</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
</body>

</html>