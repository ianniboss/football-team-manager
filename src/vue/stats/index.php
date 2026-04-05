<?php
require_once __DIR__ . '/../header.php';
?>
<link rel="stylesheet" href="/ftm/css/stats.css">
<script src="script.js" defer></script>

<div class="stats-container">
    <h1 class="page-title">📊 Statistiques Globales</h1>

    <div class="stats-grid">
        <div class="stat-card victories">
            <h3>🏆 Victoires</h3>
            <div class="number" id="statVictoires">...</div>
            <div class="percentage" id="pctVictoires">...%</div>
        </div>
        <div class="stat-card defeats">
            <h3>😔 Défaites</h3>
            <div class="number" id="statDefaites">...</div>
            <div class="percentage" id="pctDefaites">...%</div>
        </div>
        <div class="stat-card draws">
            <h3>🤝 Nuls</h3>
            <div class="number" id="statNuls">...</div>
            <div class="percentage" id="pctNuls">...%</div>
        </div>
        <div class="stat-card total">
            <h3>⚽ Total Matchs</h3>
            <div class="number" id="statTotalMatchs">...</div>
            <div class="percentage">joués</div>
        </div>
    </div>

    <h2 class="section-title">👥 Performances par Joueur</h2>

    <div class="stats-table-container">
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
                <tr>
                    <td colspan="8" style="text-align:center; padding: 30px;">
                        <em>Chargement des statistiques...</em>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>