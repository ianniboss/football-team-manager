<?php
session_start();
require_once __DIR__ . '/../header.php';
?>

<?php
$joueurs = $_SESSION['joueurs'] ?? [];
$searchQuery = $_SESSION['search_query'] ?? '';
$statusFilter = $_SESSION['status_filter'] ?? '';
?>

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

    .search-section {
        background: white;
        border-radius: 12px;
        padding: 20px 25px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .search-form {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-input-group {
        flex: 1;
        min-width: 250px;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 12px 16px 12px 45px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: border-color 0.2s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #1db988;
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.1rem;
    }

    .filter-select {
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 0.95rem;
        min-width: 150px;
        cursor: pointer;
        transition: border-color 0.2s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: #1db988;
    }

    .btn-search {
        padding: 12px 24px;
        background: linear-gradient(135deg, #1db988 0%, #17a077 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-search:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(29, 185, 136, 0.3);
    }

    .btn-reset {
        padding: 12px 20px;
        background: #f0f0f0;
        color: #555;
        border: none;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-reset:hover {
        background: #e0e0e0;
    }

    .search-results-info {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
        color: #666;
        font-size: 0.9rem;
    }

    .search-results-info strong {
        color: #1db988;
    }

    .players-table {
        width: 100%;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border-collapse: collapse;
    }

    .players-table thead {
        background-color: #2d3436;
        color: white;
    }

    .players-table th {
        padding: 16px 20px;
        text-align: left;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .players-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.95rem;
        color: #333;
    }

    .players-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .players-table tbody tr:last-child td {
        border-bottom: none;
    }

    .player-name {
        font-weight: 600;
        color: #1a1a1a;
    }

    .player-license {
        font-family: monospace;
        background-color: #f0f0f0;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-actif {
        background-color: #d4edda;
        color: #155724;
    }

    .status-blesse {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-suspendu {
        background-color: #f8d7da;
        color: #721c24;
    }

    .status-absent {
        background-color: #e2e3e5;
        color: #383d41;
    }

    .action-btn {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.85rem;
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

    .action-btn-edit {
        background-color: #fff3e0;
        color: #f57c00;
    }

    .action-btn-edit:hover {
        background-color: #ffe0b2;
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
        gap: 8px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #888;
    }

    .empty-state h3 {
        font-size: 1.2rem;
        color: #555;
        margin-bottom: 10px;
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

    .stat-label {
        font-size: 0.8rem;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

        .search-form {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input-group {
            min-width: 100%;
        }

        .players-table {
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

</div>
</body>

</html>