<?php require_once __DIR__ . '/../header.php'; ?>
<!-- utilise SaisirResultatEtEvaluations.php -->
<h2>Résultat & Évaluations</h2>
<h3>Match vs <?php echo htmlspecialchars($rencontre['nom_equipe_adverse']); ?></h3>

<form method="POST" action="SaisirResultatEtEvaluations.php">
    <input type="hidden" name="id_rencontre" value="<?php echo $rencontre['id_rencontre']; ?>">

    <label>Résultat du match :</label>
    <select name="resultat">
        <option value="Victoire" <?php echo ($rencontre['resultat'] == 'Victoire') ? 'selected' : ''; ?>>Victoire</option>
        <option value="Defaite" <?php echo ($rencontre['resultat'] == 'Defaite') ? 'selected' : ''; ?>>Défaite</option>
        <option value="Nul" <?php echo ($rencontre['resultat'] == 'Nul') ? 'selected' : ''; ?>>Nul</option>
    </select>
    <hr>

    <h4>Évaluer les joueurs présents</h4>
    <?php foreach ($joueursFeuille as $j): ?>
        <div class="player-row">
            <label><?php echo htmlspecialchars($j['nom'] . ' ' . $j['prenom']); ?> :</label>
            <input type="number" name="evaluations[<?php echo $j['id_participation']; ?>]"
                value="<?php echo $j['evaluation']; ?>" min="1" max="5" placeholder="Note /5">
        </div>
    <?php endforeach; ?>

    <br>
    <button type="submit">Enregistrer le résultat</button>
</form>
</div>
</body>

</html>