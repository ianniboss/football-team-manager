<?php
require_once __DIR__ . '/../header.php';
?>
<link rel="stylesheet" href="/ftm/css/rencontres.css">

<div class="page-header">
    <div>
        <h2>Calendrier des Rencontres</h2>
        <p class="subtitle">Gérez vos matchs et résultats</p>
    </div>
    <a href="ajouterRencontre.php" class="btn-add">
        <span>+</span> Ajouter un match
    </a>
</div>

<div class="stats-bar">
    <div class="stat-item">
        <div class="stat-number" id="statTotal">...</div>
        <div class="stat-label">Total Matchs</div>
    </div>
    <div class="stat-item">
        <div class="stat-number victories" id="statVictoires">...</div>
        <div class="stat-label">Victoires</div>
    </div>
    <div class="stat-item">
        <div class="stat-number defeats" id="statDefaites">...</div>
        <div class="stat-label">Défaites</div>
    </div>
    <div class="stat-item">
        <div class="stat-number draws" id="statNuls">...</div>
        <div class="stat-label">Nuls</div>
    </div>
    <div class="stat-item">
        <div class="stat-number" id="statAVenir">...</div>
        <div class="stat-label">À Venir</div>
    </div>
</div>

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
        <tr>
            <td colspan="6" style="text-align:center; padding: 40px;">
                <em>Chargement des rencontres...</em>
            </td>
        </tr>
    </tbody>
</table>

<script src="script.js"></script>
<?php require_once __DIR__ . '/../footer.php'; ?>