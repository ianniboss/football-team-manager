<?php
require_once __DIR__ . '/../header.php';
$statsGlobales = $_SESSION['statsGlobales'] ?? [];
$tableauJoueurs = $_SESSION['tableauJoueurs'] ?? [];
?>
<link rel="stylesheet" href="/ftm/css/stats.css">

<div class="stats-container">
    <h1 class="page-title">📊 Statistiques Globales</h1>

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
                                    <?php if (!empty($j['image'])): ?>
                                        <img src="/modele/img/players/<?= htmlspecialchars($j['image']); ?>" alt="Photo"
                                            style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #e0e0e0;">
                                    <?php else: ?>
                                        <div class="player-avatar">
                                            <?= strtoupper(substr($j['prenom'], 0, 1) . substr($j['nom'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
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

<?php require_once __DIR__ . '/../footer.php'; ?>