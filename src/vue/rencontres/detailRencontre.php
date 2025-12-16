<?php require_once __DIR__ . '/../header.php'; ?>
<!-- utilise RechercherUneRencontre.php -->
<a href="ObtenirToutesLesRencontres.php">← Retour</a>

<h2>Match contre <?php echo htmlspecialchars($rencontre['nom_equipe_adverse']); ?></h2>
<p>
    <strong>Date :</strong> <?php echo $rencontre['date_rencontre']; ?><br>
    <strong>Lieu :</strong> <?php echo $rencontre['lieu']; ?> (<?php echo $rencontre['adresse']; ?>)<br>
    <strong>Résultat :</strong> <?php echo $rencontre['resultat'] ?? 'Non joué'; ?>
</p>

<div class="actions">
    <a href="ModifierUneRencontre.php?id=<?php echo $rencontre['id_rencontre']; ?>" class="btn">Modifier infos</a>
    <a href="SaisirResultatEtEvaluations.php?id=<?php echo $rencontre['id_rencontre']; ?>" class="btn">Saisir Résultat /
        Notes</a>
    <a href="../selection/AfficherSelection.php?id_rencontre=<?php echo $rencontre['id_rencontre']; ?>"
        class="btn">Gérer la sélection</a>
</div>

<h3>Feuille de match (Joueurs convoqués)</h3>
<ul>
    <?php if (empty($joueursParticipe)): ?>
        <li>Aucun joueur sélectionné pour le moment.</li>
    <?php else: ?>
        <?php foreach ($joueursParticipe as $j): ?>
            <li>
                <?php echo htmlspecialchars($j['nom'] . ' ' . $j['prenom']); ?>
                - <?php echo $j['titulaire'] ? '<strong>Titulaire</strong>' : 'Remplaçant'; ?>
                (Poste : <?php echo htmlspecialchars($j['poste']); ?>)
                <?php if ($j['evaluation'])
                    echo "- Note: " . $j['evaluation'] . "/5"; ?>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
</div>
</body>

</html>