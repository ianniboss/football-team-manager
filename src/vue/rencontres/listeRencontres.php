<?php
require_once __DIR__ . '/../header.php';
$rencontres = $_SESSION['rencontres'] ?? [];
?>
<link rel="stylesheet" href="/css/rencontres.css">

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

<?php require_once __DIR__ . '/../footer.php'; ?>