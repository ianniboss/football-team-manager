<?php
require_once __DIR__ . '/../header.php';

$rencontre = $_SESSION['rencontre_detail'] ?? null;
$joueursParticipe = $_SESSION['joueurs_participe'] ?? [];

if (!$rencontre) {
    header("Location: /vue/rencontres/listeRencontres.php");
    exit;
}
?>
<!-- utilise RechercherUneRencontre.php -->
<link rel="stylesheet" href="/css/rencontres.css">

<div class="match-detail-container">
    <a href="/controleur/rencontre/ObtenirToutesLesRencontres.php" class="back-link">
        ← Retour au calendrier
    </a>

    <?php
    $hasStadiumImage = !empty($rencontre['image_stade']);
    $stadiumStyle = $hasStadiumImage
        ? "background: linear-gradient(135deg, rgba(45, 52, 54, 0.9) 0%, rgba(0, 0, 0, 0.85) 100%), url('/modele/img/matchs/" . htmlspecialchars($rencontre['image_stade']) . "'); background-size: cover; background-position: center;"
        : "background: linear-gradient(135deg, #2d3436 0%, #000000 100%);";
    ?>
    <div class="match-header" style="<?= $stadiumStyle ?>">
        <h1>Match contre <?php echo htmlspecialchars($rencontre['nom_equipe_adverse']); ?></h1>

        <div class="match-meta">
            <div class="meta-item">
                <span class="label">Date</span>
                <span class="value"><?php echo $rencontre['date_rencontre']; ?></span>
            </div>
            <div class="meta-item">
                <span class="label">Heure</span>
                <span class="value"><?php echo $rencontre['heure']; ?></span>
            </div>
            <div class="meta-item">
                <span class="label">Lieu</span>
                <?php $venueClass = $rencontre['lieu'] === 'Domicile' ? 'venue-domicile' : 'venue-exterieur'; ?>
                <span class="venue-badge <?= $venueClass ?>"><?php echo $rencontre['lieu']; ?></span>
            </div>
            <div class="meta-item">
                <span class="label">Adresse</span>
                <span class="value"><?php echo htmlspecialchars($rencontre['adresse']); ?></span>
            </div>
            <div class="meta-item">
                <span class="label">Résultat</span>
                <?php
                $resultat = $rencontre['resultat'];
                if ($resultat === 'Victoire') {
                    $resultClass = 'result-victoire';
                    $resultText = '✓ Victoire';
                } else if ($resultat === 'Defaite') {
                    $resultClass = 'result-defaite';
                    $resultText = '✗ Défaite';
                } else if ($resultat === 'Nul') {
                    $resultClass = 'result-nul';
                    $resultText = '= Nul';
                } else {
                    $resultClass = 'result-avenir';
                    $resultText = '⏱ À jouer';
                }
                ?>
                <span class="result-badge <?= $resultClass ?>"><?= $resultText ?></span>
            </div>
        </div>
    </div>

    <?php
    // Check if the match date is in the past
    $matchDate = new DateTime($rencontre['date_rencontre']);
    $today = new DateTime('today');
    $isMatchPast = $matchDate < $today;
    ?>

    <?php if ($isMatchPast): ?>
        <div class="match-locked-notice">
            <span class="icon">🔒</span>
            <span>Ce match est passé. Seule la saisie du résultat et des évaluations est disponible.</span>
        </div>
    <?php endif; ?>

    <div class="actions-grid">
        <a href="<?php echo $isMatchPast ? '#' : '/controleur/rencontre/ModifierUneRencontre.php?id=' . $rencontre['id_rencontre']; ?>"
            class="action-card <?php echo $isMatchPast ? 'disabled' : ''; ?>">
            <div class="icon">✏️</div>
            <h4>Modifier infos</h4>
            <p>Éditer les détails du match</p>
        </a>
        <a href="/controleur/rencontre/SaisirResultatEtEvaluations.php?id=<?php echo $rencontre['id_rencontre']; ?>"
            class="action-card">
            <div class="icon">📊</div>
            <h4>Saisir Résultat</h4>
            <p>Entrer le score et les notes</p>
        </a>
        <a href="<?php echo $isMatchPast ? '#' : '/controleur/selection/AfficherSelection.php?id_rencontre=' . $rencontre['id_rencontre']; ?>"
            class="action-card <?php echo $isMatchPast ? 'disabled' : ''; ?>">
            <div class="icon">👥</div>
            <h4>Gérer la sélection</h4>
            <p>Convoquer les joueurs</p>
        </a>
    </div>

    <div class="players-section">
        <h3>📋 Feuille de match</h3>

        <?php if (empty($joueursParticipe)): ?>
            <div class="empty-state">
                <p>Aucun joueur sélectionné pour ce match.</p>
            </div>
        <?php else: ?>
            <ul class="player-list">
                <?php foreach ($joueursParticipe as $j): ?>
                    <li class="player-item">
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
                                <div class="player-role"><?php echo htmlspecialchars($j['poste']); ?></div>
                            </div>
                        </div>
                        <div class="player-stats">
                            <span class="titulaire-badge <?= $j['titulaire'] ? 'titular' : 'sub' ?>">
                                <?= $j['titulaire'] ? 'Titulaire' : 'Remplaçant' ?>
                            </span>
                            <?php if ($j['evaluation']): ?>
                                <div class="rating">
                                    ⭐ <?php echo $j['evaluation']; ?>/5
                                </div>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>