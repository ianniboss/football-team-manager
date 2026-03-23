<?php
session_start();
require_once __DIR__ . '/../header.php';
?>

<?php
$joueurs = $_SESSION['joueurs'] ?? [];
$searchQuery = $_SESSION['search_query'] ?? '';
$statusFilter = $_SESSION['status_filter'] ?? '';
?>
<link rel="stylesheet" href="/ftm/css/joueurs.css">

<div class="page-header">
    <div>
        <h2>Liste des Joueurs</h2>
        <p class="subtitle">Gérez votre effectif</p>
    </div>
    <a href="/controleur/joueur/AjouterJoueur.php" class="btn-add">
        <span>+</span> Ajouter un joueur
    </a>
</div>

<?php
require_once __DIR__ . '/../../modele/JoueurDAO.php';
$dao = new JoueurDAO();
$allJoueurs = $dao->getJoueurs();
$totalJoueurs = count($allJoueurs);
$joueursActifs = count(array_filter($allJoueurs, fn($j) => $j['statut'] === 'Actif'));
$joueursBlessés = count(array_filter($allJoueurs, fn($j) => $j['statut'] === 'Blessé'));
?>

<div class="stats-bar">
    <div class="stat-item">
        <div class="stat-number"><?= $totalJoueurs ?></div>
        <div class="stat-label">Total Joueurs</div>
    </div>
    <div class="stat-item">
        <div class="stat-number"><?= $joueursActifs ?></div>
        <div class="stat-label">Actifs</div>
    </div>
    <div class="stat-item">
        <div class="stat-number"><?= $joueursBlessés ?></div>
        <div class="stat-label">Blessés</div>
    </div>
</div>

<div class="search-section">
    <form action="/controleur/joueur/ObtenirTousLesJoueurs.php" method="GET" class="search-form">
        <div class="search-input-group">
            <span class="search-icon">🔍</span>
            <input type="text" name="search" class="search-input" placeholder="Rechercher par nom ou n° licence..."
                value="<?= htmlspecialchars($searchQuery) ?>">
        </div>
        <select name="statut" class="filter-select">
            <option value="">Tous les statuts</option>
            <option value="Actif" <?= $statusFilter === 'Actif' ? 'selected' : '' ?>>Actif</option>
            <option value="Blessé" <?= $statusFilter === 'Blessé' ? 'selected' : '' ?>>Blessé</option>
            <option value="Suspendu" <?= $statusFilter === 'Suspendu' ? 'selected' : '' ?>>Suspendu</option>
            <option value="Absent" <?= $statusFilter === 'Absent' ? 'selected' : '' ?>>Absent</option>
        </select>
        <button type="submit" class="btn-search">Rechercher</button>
        <a href="/controleur/joueur/ObtenirTousLesJoueurs.php" class="btn-reset">Réinitialiser</a>
    </form>

    <?php if (!empty($searchQuery) || !empty($statusFilter)): ?>
        <div class="search-results-info">
            <?php
            $resultCount = count($joueurs);
            $filterInfo = [];
            if (!empty($searchQuery))
                $filterInfo[] = "« " . htmlspecialchars($searchQuery) . " »";
            if (!empty($statusFilter))
                $filterInfo[] = "statut: " . htmlspecialchars($statusFilter);
            ?>
            <strong><?= $resultCount ?></strong> joueur(s) trouvé(s)
            pour <?= implode(', ', $filterInfo) ?>
        </div>
    <?php endif; ?>
</div>

<?php if (empty($joueurs)): ?>
    <div class="empty-state">
        <h3>Aucun joueur enregistré</h3>
        <p>Commencez par ajouter votre premier joueur à l'équipe.</p>
    </div>
<?php else: ?>
    <table class="players-table">
        <thead>
            <tr>
                <th>Joueur</th>
                <th>N° Licence</th>
                <th>Taille</th>
                <th>Poids</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($joueurs as $j): ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <?php if (!empty($j['image'])): ?>
                                <img src="/modele/img/players/<?= htmlspecialchars($j['image']); ?>" alt="Photo"
                                    style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #e0e0e0;">
                            <?php else: ?>
                                <div
                                    style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #1db988, #17a077); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                    <?= strtoupper(substr($j['prenom'], 0, 1) . substr($j['nom'], 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            <span class="player-name"><?= htmlspecialchars($j['prenom'] . ' ' . $j['nom']); ?></span>
                        </div>
                    </td>
                    <td>
                        <span class="player-license"><?= htmlspecialchars($j['num_licence']); ?></span>
                    </td>
                    <td><?= htmlspecialchars($j['taille'] ?? '-'); ?> cm</td>
                    <td><?= htmlspecialchars($j['poids'] ?? '-'); ?> kg</td>
                    <td>
                        <?php
                        $statut = $j['statut'];
                        $statusClass = 'status-actif';
                        if ($statut === 'Blessé')
                            $statusClass = 'status-blesse';
                        else if ($statut === 'Suspendu')
                            $statusClass = 'status-suspendu';
                        else if ($statut === 'Absent')
                            $statusClass = 'status-absent';
                        ?>
                        <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($statut); ?></span>
                    </td>
                    <td>
                        <div class="actions-cell">
                            <a href="/controleur/joueur/ObtenirUnJoueur.php?id=<?= $j['id_joueur']; ?>"
                                class="action-btn action-btn-view">Voir</a>
                            <a href="/controleur/joueur/ModifierIdentiteDuJoueur.php?id=<?= $j['id_joueur']; ?>"
                                class="action-btn action-btn-edit">Modifier</a>
                            <a href="/controleur/joueur/SupprimerUnJoueur.php?id=<?= $j['id_joueur']; ?>"
                                class="action-btn action-btn-delete"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce joueur ?');">Supprimer</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once __DIR__ . '/../footer.php'; ?>