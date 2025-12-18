<?php require_once __DIR__ . '/../header.php'; ?>
<!-- utilise RechercherUneRencontre.php -->

<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #1db988;
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 25px;
        transition: color 0.2s ease;
    }

    .back-link:hover {
        color: #17a077;
    }

    .match-detail-container {
        max-width: 900px;
        margin: 0 auto;
    }

    /* Match Header Card */
    .match-header {
        background: linear-gradient(135deg, #2d3436 0%, #000000 100%);
        border-radius: 16px;
        padding: 40px;
        color: white;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
    }

    .match-header::before {
        content: '⚽';
        position: absolute;
        right: 30px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 6rem;
        opacity: 0.1;
    }

    .match-header h1 {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0 0 20px 0;
    }

    .match-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
    }

    .meta-item .label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 5px;
    }

    .meta-item .value {
        font-size: 1.1rem;
        font-weight: 500;
    }

    /* Result Badge */
    .result-badge {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .result-victoire {
        background: #28a745;
        color: white;
    }

    .result-defaite {
        background: #dc3545;
        color: white;
    }

    .result-nul {
        background: #6c757d;
        color: white;
    }

    .result-avenir {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    /* Venue Badge */
    .venue-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .venue-domicile {
        background: #1db988;
        color: white;
    }

    .venue-exterieur {
        background: #f39c12;
        color: white;
    }

    /* Action Cards */
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }

    .action-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-decoration: none;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
        border: 2px solid transparent;
    }

    .action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-color: #1db988;
    }

    .action-card .icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .action-card h4 {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 5px 0;
    }

    .action-card p {
        font-size: 0.8rem;
        color: #888;
        margin: 0;
    }

    /* Players Section */
    .players-section {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .players-section h3 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .player-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .player-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .player-item:last-child {
        border-bottom: none;
    }

    .player-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .player-avatar {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1rem;
    }

    .player-name {
        font-weight: 600;
        color: #1a1a1a;
    }

    .player-role {
        font-size: 0.85rem;
        color: #888;
        margin-top: 2px;
    }

    .player-stats {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .titulaire-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .titulaire-badge.titular {
        background: #d4edda;
        color: #155724;
    }

    .titulaire-badge.sub {
        background: #e2e3e5;
        color: #383d41;
    }

    .rating {
        display: flex;
        align-items: center;
        gap: 5px;
        font-weight: 600;
        color: #f39c12;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #888;
    }

    .empty-state p {
        margin: 0;
    }

    @media (max-width: 768px) {
        .match-header {
            padding: 25px;
        }

        .match-meta {
            gap: 20px;
        }

        .actions-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<div class="match-detail-container">
    <a href="/controleur/rencontre/ObtenirToutesLesRencontres.php" class="back-link">
        ← Retour au calendrier
    </a>

    <!-- Match Header -->
    <div class="match-header">
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

    <!-- Action Cards -->
    <div class="actions-grid">
        <a href="/controleur/rencontre/ModifierUneRencontre.php?id=<?php echo $rencontre['id_rencontre']; ?>"
            class="action-card">
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
        <a href="/controleur/selection/AfficherSelection.php?id_rencontre=<?php echo $rencontre['id_rencontre']; ?>"
            class="action-card">
            <div class="icon">👥</div>
            <h4>Gérer la sélection</h4>
            <p>Convoquer les joueurs</p>
        </a>
    </div>

    <!-- Players Section -->
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
                            <div class="player-avatar">
                                <?= strtoupper(substr($j['prenom'], 0, 1) . substr($j['nom'], 0, 1)) ?>
                            </div>
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

</div>
</body>

</html>