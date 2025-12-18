<?php
// Session already started by controller (AfficherStatistiques.php)
require_once __DIR__ . '/../header.php';
?>

<style>
    .stats-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 30px 0;
    }

    /* Global Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        text-align: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .stat-card.victories {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-left: 4px solid #28a745;
    }

    .stat-card.defeats {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border-left: 4px solid #dc3545;
    }

    .stat-card.draws {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
        border-left: 4px solid #ffc107;
    }

    .stat-card.total {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-left: 4px solid #2196f3;
    }

    .stat-card h3 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #555;
        margin: 0 0 10px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card .number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1a1a1a;
        line-height: 1;
    }

    .stat-card .percentage {
        font-size: 1rem;
        color: #666;
        margin-top: 8px;
    }

    /* Section Title */
    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Player Stats Table */
    .stats-table-container {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .stats-table {
        width: 100%;
        border-collapse: collapse;
    }

    .stats-table thead {
        background: #2d3436;
        color: white;
    }

    .stats-table th {
        padding: 15px 12px;
        text-align: left;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-table th:not(:first-child) {
        text-align: center;
    }

    .stats-table td {
        padding: 15px 12px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.95rem;
    }

    .stats-table td:not(:first-child) {
        text-align: center;
    }

    .stats-table tbody tr:hover {
        background: #f8f9fa;
    }

    .stats-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Player Cell */
    .player-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .player-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .player-name {
        font-weight: 600;
        color: #1a1a1a;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-actif {
        background: #d4edda;
        color: #155724;
    }

    .status-blesse {
        background: #f8d7da;
        color: #721c24;
    }

    .status-suspendu {
        background: #fff3cd;
        color: #856404;
    }

    .status-absent {
        background: #e2e3e5;
        color: #383d41;
    }

    /* Position Badge */
    .position-badge {
        display: inline-block;
        padding: 4px 10px;
        background: #e3f2fd;
        color: #1976d2;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    /* Rating Stars */
    .rating {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-weight: 700;
        color: #f39c12;
    }

    /* Percentage with Progress */
    .pct-cell {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
    }

    .pct-value {
        font-weight: 600;
        color: #28a745;
    }

    .pct-bar {
        width: 60px;
        height: 6px;
        background: #e0e0e0;
        border-radius: 3px;
        overflow: hidden;
    }

    .pct-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
        border-radius: 3px;
    }

    /* Series Badge */
    .serie-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .serie-w {
        background: #d4edda;
        color: #155724;
    }

    .serie-l {
        background: #f8d7da;
        color: #721c24;
    }

    .serie-d {
        background: #e2e3e5;
        color: #383d41;
    }

    /* Number highlight */
    .num-highlight {
        font-weight: 700;
        color: #1a1a1a;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #888;
    }

    @media (max-width: 900px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .stats-table-container {
            overflow-x: auto;
        }
    }

    @media (max-width: 500px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="stats-container">
    <h1 class="page-title">📊 Statistiques Globales</h1>

    <!-- Global Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card victories">
            <h3>🏆 Victoires</h3>
            <div class="number"><?php echo $statsGlobales['victoires']; ?></div>
            <div class="percentage"><?php echo $statsGlobales['pct_victoires']; ?>%</div>
        </div>
        <div class="stat-card defeats">
            <h3>😔 Défaites</h3>
            <div class="number"><?php echo $statsGlobales['defaites']; ?></div>
            <div class="percentage"><?php echo $statsGlobales['pct_defaites']; ?>%</div>
        </div>
        <div class="stat-card draws">
            <h3>🤝 Nuls</h3>
            <div class="number"><?php echo $statsGlobales['nuls']; ?></div>
            <div class="percentage"><?php echo $statsGlobales['pct_nuls']; ?>%</div>
        </div>
        <div class="stat-card total">
            <h3>⚽ Total Matchs</h3>
            <div class="number"><?php echo $statsGlobales['total_joues']; ?></div>
            <div class="percentage">joués</div>
        </div>
    </div>

    <!-- Player Stats -->
    <h2 class="section-title">👥 Performances par Joueur</h2>

    <div class="stats-table-container">
        <?php if (empty($tableauJoueurs)): ?>
            <div class="empty-state">
                <p>Aucune statistique de joueur disponible.</p>
            </div>
        <?php else: ?>
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Joueur</th>
                        <th>Statut</th>
                        <th>Poste</th>
                        <th>Titulaire</th>
                        <th>Remplaçant</th>
                        <th>Note Moy.</th>
                        <th>% Victoires</th>
                        <th>Série</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tableauJoueurs as $j):
                        // Status class
                        $statut = $j['statut'];
                        $statusClass = 'status-actif';
                        if ($statut === 'Blessé')
                            $statusClass = 'status-blesse';
                        else if ($statut === 'Suspendu')
                            $statusClass = 'status-suspendu';
                        else if ($statut === 'Absent')
                            $statusClass = 'status-absent';

                        // Series class
                        $serie = $j['serie_cours'] ?? '-';
                        $serieClass = 'serie-d';
                        if (strpos($serie, 'V') !== false)
                            $serieClass = 'serie-w';
                        else if (strpos($serie, 'D') !== false)
                            $serieClass = 'serie-l';
                        ?>
                        <tr>
                            <td>
                                <div class="player-cell">
                                    <div class="player-avatar">
                                        <?= strtoupper(substr($j['prenom'], 0, 1) . substr($j['nom'], 0, 1)) ?>
                                    </div>
                                    <span
                                        class="player-name"><?php echo htmlspecialchars($j['prenom'] . ' ' . $j['nom']); ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($statut) ?></span>
                            </td>
                            <td>
                                <?php if (!empty($j['poste_prefere'])): ?>
                                    <span class="position-badge"><?php echo htmlspecialchars($j['poste_prefere']); ?></span>
                                <?php else: ?>
                                    <span style="color: #888;">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="num-highlight"><?php echo $j['titularisations']; ?></span>
                            </td>
                            <td>
                                <span class="num-highlight"><?php echo $j['remplacements']; ?></span>
                            </td>
                            <td>
                                <?php if ($j['moyenne_notes'] && $j['moyenne_notes'] > 0): ?>
                                    <span class="rating">⭐ <?php echo $j['moyenne_notes']; ?></span>
                                <?php else: ?>
                                    <span style="color: #888;">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="pct-cell">
                                    <span class="pct-value"><?php echo $j['pct_gagne']; ?>%</span>
                                    <div class="pct-bar">
                                        <div class="pct-bar-fill" style="width: <?php echo $j['pct_gagne']; ?>%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="serie-badge <?= $serieClass ?>"><?php echo $serie; ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

</div>
</body>

</html>