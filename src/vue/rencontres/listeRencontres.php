<?php require_once __DIR__ . '/../header.php'; ?>
<!-- utilise ObtenirToutesLesRencontres.php -->

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-header h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .page-header .subtitle {
        color: #888;
        font-size: 0.95rem;
        margin-top: 5px;
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: #1db988;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .btn-add:hover {
        background-color: #17a077;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(29, 185, 136, 0.3);
    }

    .stats-bar {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
    }

    .stat-item {
        background: white;
        padding: 15px 25px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1db988;
    }

    .stat-number.victories {
        color: #28a745;
    }

    .stat-number.defeats {
        color: #dc3545;
    }

    .stat-number.draws {
        color: #6c757d;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .matches-table {
        width: 100%;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border-collapse: collapse;
    }

    .matches-table thead {
        background-color: #2d3436;
        color: white;
    }

    .matches-table th {
        padding: 16px 20px;
        text-align: left;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .matches-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.95rem;
        color: #333;
    }

    .matches-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .matches-table tbody tr:last-child td {
        border-bottom: none;
    }

    .match-date {
        display: flex;
        flex-direction: column;
    }

    .match-date .date {
        font-weight: 600;
        color: #1a1a1a;
    }

    .match-date .time {
        font-size: 0.85rem;
        color: #888;
    }

    .opponent-name {
        font-weight: 600;
        color: #1a1a1a;
    }

    .venue-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .venue-domicile {
        background-color: #e3f2fd;
        color: #1976d2;
    }

    .venue-exterieur {
        background-color: #fff3e0;
        color: #f57c00;
    }

    .result-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .result-victoire {
        background-color: #d4edda;
        color: #155724;
    }

    .result-defaite {
        background-color: #f8d7da;
        color: #721c24;
    }

    .result-nul {
        background-color: #e2e3e5;
        color: #383d41;
    }

    .result-avenir {
        background-color: #cce5ff;
        color: #004085;
    }

    .action-btn {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .action-btn-view {
        background-color: #e3f2fd;
        color: #1976d2;
    }

    .action-btn-view:hover {
        background-color: #bbdefb;
    }

    .action-btn-sheet {
        background-color: #e8f5e9;
        color: #388e3c;
    }

    .action-btn-sheet:hover {
        background-color: #c8e6c9;
    }

    .action-btn-delete {
        background-color: #ffebee;
        color: #d32f2f;
    }

    .action-btn-delete:hover {
        background-color: #ffcdd2;
    }

    .actions-cell {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #888;
        background: white;
        border-radius: 12px;
    }

    .empty-state h3 {
        font-size: 1.2rem;
        color: #555;
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .stats-bar {
            flex-wrap: wrap;
        }

        .matches-table {
            display: block;
            overflow-x: auto;
        }

        .actions-cell {
            flex-direction: column;
        }
    }
</style>

<div class="page-header">
    <div>
        <h2>Calendrier des Rencontres</h2>
        <p class="subtitle">Gérez vos matchs et résultats</p>
    </div>
    <a href="/controleur/rencontre/ajouterRencontre.php" class="btn-add">
        <span>+</span> Ajouter un match
    </a>
</div>

<?php
$totalMatchs = count($rencontres);
$victoires = count(array_filter($rencontres, fn($r) => $r['resultat'] === 'Victoire'));
$defaites = count(array_filter($rencontres, fn($r) => $r['resultat'] === 'Defaite'));
$nuls = count(array_filter($rencontres, fn($r) => $r['resultat'] === 'Nul'));
$aVenir = count(array_filter($rencontres, fn($r) => $r['resultat'] === null));
?>

<div class="stats-bar">
    <div class="stat-item">
        <div class="stat-number"><?= $totalMatchs ?></div>
        <div class="stat-label">Total Matchs</div>
    </div>
    <div class="stat-item">
        <div class="stat-number victories"><?= $victoires ?></div>
        <div class="stat-label">Victoires</div>
    </div>
    <div class="stat-item">
        <div class="stat-number defeats"><?= $defaites ?></div>
        <div class="stat-label">Défaites</div>
    </div>
    <div class="stat-item">
        <div class="stat-number draws"><?= $nuls ?></div>
        <div class="stat-label">Nuls</div>
    </div>
    <div class="stat-item">
        <div class="stat-number"><?= $aVenir ?></div>
        <div class="stat-label">À Venir</div>
    </div>
</div>

<?php if (empty($rencontres)): ?>
    <div class="empty-state">
        <h3>Aucune rencontre programmée</h3>
        <p>Commencez par ajouter votre premier match.</p>
    </div>
<?php else: ?>
    <table class="matches-table">
        <thead>
            <tr>
                <th>Date & Heure</th>
                <th>Adversaire</th>
                <th>Lieu</th>
                <th>Adresse</th>
                <th>Résultat</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rencontres as $r): ?>
                <tr>
                    <td>
                        <div class="match-date">
                            <span class="date"><?= htmlspecialchars($r['date_rencontre']); ?></span>
                            <span class="time"><?= htmlspecialchars($r['heure']); ?></span>
                        </div>
                    </td>
                    <td>
                        <span class="opponent-name"><?= htmlspecialchars($r['nom_equipe_adverse']); ?></span>
                    </td>
                    <td>
                        <?php
                        $venueClass = $r['lieu'] === 'Domicile' ? 'venue-domicile' : 'venue-exterieur';
                        ?>
                        <span class="venue-badge <?= $venueClass ?>"><?= htmlspecialchars($r['lieu']); ?></span>
                    </td>
                    <td><?= htmlspecialchars($r['adresse']); ?></td>
                    <td>
                        <?php
                        $resultat = $r['resultat'];
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
                            $resultText = '⏱ À venir';
                        }
                        ?>
                        <span class="result-badge <?= $resultClass ?>"><?= $resultText ?></span>
                    </td>
                    <td>
                        <div class="actions-cell">
                            <a href="/controleur/rencontre/RechercherUneRencontre.php?id=<?= $r['id_rencontre']; ?>"
                                class="action-btn action-btn-view">Détails</a>
                            <?php if (!$r['resultat']): ?>
                                <a href="/controleur/selection/AfficherSelection.php?id_rencontre=<?= $r['id_rencontre']; ?>"
                                    class="action-btn action-btn-sheet">Feuille de match</a>
                            <?php endif; ?>
                            <a href="/controleur/rencontre/SupprimerUneRencontre.php?id=<?= $r['id_rencontre']; ?>"
                                class="action-btn action-btn-delete"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?');">Supprimer</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</div>
</body>

</html>