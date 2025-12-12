<?php require_once __DIR__ . '/../header.php'; ?>
<!-- utilise ajouterRencontre.php AND ModifierUneRencontre.php -->
<h2><?php echo isset($rencontre) ? 'Modifier le match' : 'Nouveau match'; ?></h2>

<form method="POST" action="<?php echo isset($rencontre) ? 'ModifierUneRencontre.php' : 'ajouterRencontre.php'; ?>">
    <?php if (isset($rencontre)): ?>
        <input type="hidden" name="id_rencontre" value="<?php echo $rencontre['id_rencontre']; ?>">
    <?php endif; ?>

    <label>Date :</label>
    <input type="date" name="date_rencontre" value="<?php echo $rencontre['date_rencontre'] ?? ''; ?>" required><br>

    <label>Heure :</label>
    <input type="time" name="heure" value="<?php echo $rencontre['heure'] ?? ''; ?>" required><br>

    <label>Adversaire :</label>
    <input type="text" name="nom_equipe_adverse" value="<?php echo $rencontre['nom_equipe_adverse'] ?? ''; ?>"
        required><br>

    <label>Lieu :</label>
    <select name="lieu">
        <option value="Domicile" <?php echo (isset($rencontre) && $rencontre['lieu'] == 'Domicile') ? 'selected' : ''; ?>>
            Domicile</option>
        <option value="Exterieur" <?php echo (isset($rencontre) && $rencontre['lieu'] == 'Exterieur') ? 'selected' : ''; ?>>Extérieur</option>
    </select><br>

    <label>Adresse :</label>
    <input type="text" name="adresse" value="<?php echo $rencontre['adresse'] ?? ''; ?>" required><br>

    <button type="submit">Enregistrer</button>
</form>
</div>
</body>

</html>