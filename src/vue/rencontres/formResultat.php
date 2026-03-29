<?php
require_once __DIR__ . '/../header.php';
$rencontre = $_SESSION['rencontre_resultat'] ?? null;
$joueursFeuille = $_SESSION['joueurs_feuille_resultat'] ?? [];

if (!$rencontre) {
    header("Location: /ftm/vue/rencontres/listeRencontres.php");
    exit;
}
?>
<!-- utilise SaisirResultatEtEvaluations.php -->
<link rel="stylesheet" href="/ftm/css/rencontres.css">

<div class="result-form-container">
    <a href="/api/rencontre/RechercherUneRencontre.php?id=<?php echo $rencontre['id_rencontre']; ?>" class="back-link">
        ← Retour aux détails
    </a>

    <div class="match-info-header">
        <h1>📊 Résultat & Évaluations</h1>
        <p>Match contre <?php echo htmlspecialchars($rencontre['nom_equipe_adverse']); ?> - <?php echo $rencontre['date_rencontre']; ?></p>
    </div>

    <form method="POST" action="/api/rencontre/SaisirResultatEtEvaluations.php">
        <input type="hidden" name="id_rencontre" value="<?php echo $rencontre['id_rencontre']; ?>">

        <div class="result-card">
            <h3>🏆 Résultat du match</h3>
            <div class="result-options">
                <div class="result-option victoire">
                    <input type="radio" name="resultat" id="victoire" value="Victoire"
                        <?php echo ($rencontre['resultat'] == 'Victoire') ? 'checked' : ''; ?>>
                    <label for="victoire">
                        <span class="icon">🎉</span>
                        <span class="text">Victoire</span>
                    </label>
                </div>
                <div class="result-option nul">
                    <input type="radio" name="resultat" id="nul" value="Nul"
                        <?php echo ($rencontre['resultat'] == 'Nul') ? 'checked' : ''; ?>>
                    <label for="nul">
                        <span class="icon">🤝</span>
                        <span class="text">Match Nul</span>
                    </label>
                </div>
                <div class="result-option defaite">
                    <input type="radio" name="resultat" id="defaite" value="Defaite"
                        <?php echo ($rencontre['resultat'] == 'Defaite') ? 'checked' : ''; ?>>
                    <label for="defaite">
                        <span class="icon">😔</span>
                        <span class="text">Défaite</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="evaluations-card">
            <h3>⭐ Évaluer les joueurs</h3>

            <?php if (empty($joueursFeuille)): ?>
                <div class="empty-state">
                    <p>Aucun joueur n'a été convoqué pour ce match.</p>
                </div>
            <?php else: ?>
                <div class="player-eval-list">
                    <?php foreach ($joueursFeuille as $j): ?>
                        <div class="player-eval-row">
                            <div class="player-info">
                                <?php if (!empty($j['image'])): ?>
                                    <img src="/modele/img/players/<?php echo htmlspecialchars($j['image']); ?>"
                                        alt="Photo de <?php echo htmlspecialchars($j['prenom']); ?>"
                                        style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #e0e0e0;">
                                <?php else: ?>
                                    <div class="player-avatar">
                                        <?= strtoupper(substr($j['prenom'], 0, 1) . substr($j['nom'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="player-name"><?php echo htmlspecialchars($j['prenom'] . ' ' . $j['nom']); ?></div>
                                    <div class="player-role"><?= $j['titulaire'] ? 'Titulaire' : 'Remplaçant' ?></div>
                                </div>
                            </div>
                            <div class="star-rating">
                                <input type="number" name="evaluations[<?php echo $j['id_participation']; ?>]"
                                    value="<?php echo $j['evaluation']; ?>" min="1" max="5" placeholder="-">
                                <span>/ 5 ⭐</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">
                ✓ Enregistrer le résultat
            </button>
            <a href="/api/rencontre/RechercherUneRencontre.php?id=<?php echo $rencontre['id_rencontre']; ?>" class="btn-cancel">
                Annuler
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>