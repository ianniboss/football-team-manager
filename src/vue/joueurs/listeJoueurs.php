<?php
session_start();
require_once __DIR__ . '/../header.php';
?>

<?php $joueurs = $_SESSION['joueurs'] ?? []; ?>

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

    /* Status badges */
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

    /* Action buttons */
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

    /* Empty state */
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

    /* Stats bar */
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
// Calculate stats
$totalJoueurs = count($joueurs);
$joueursActifs = count(array_filter($joueurs, fn($j) => $j['statut'] === 'Actif'));
$joueursBlessés = count(array_filter($joueurs, fn($j) => $j['statut'] === 'Blessé'));
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
                        <span class="player-name"><?= htmlspecialchars($j['prenom'] . ' ' . $j['nom']); ?></span>
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