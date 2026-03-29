<?php
require_once __DIR__ . '/../header.php';
?>
<link rel="stylesheet" href="/ftm/css/joueurs.css">

<div class="page-header">
    <div>
        <h2>Liste des Joueurs</h2>
        <p class="subtitle">Gérez votre effectif</p>
    </div>
    <a href="/ftm/vue/joueurs/ajouterJoueur.php" class="btn-add">
        <span>+</span> Ajouter un joueur
    </a>
</div>

<div class="stats-bar">
    <div class="stat-item">
        <div class="stat-number">...</div>
        <div class="stat-label">Total Joueurs</div>
    </div>
    <div class="stat-item">
        <div class="stat-number">...</div>
        <div class="stat-label">Joueurs Actifs</div>
    </div>
    <div class="stat-item">
        <div class="stat-number">...</div>
        <div class="stat-label">Joueurs Blessés</div>
    </div>
</div>

<div class="search-section">
    <form class="search-form">
        <div class="search-input-group">
            <span class="search-icon">🔍</span>
            <input type="text" name="search" placeholder="Rechercher un joueur...">
        </div>
        <select name="statut" class="filter-select">
            <option value="">Tous les statuts</option>
            <option value="Actif">Actif</option>
            <option value="Blessé">Blessé</option>
            <option value="Suspendu">Suspendu</option>
            <option value="Absent">Absent</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filtrer</button>
    </form>
</div>

<table class="players-table">
    <thead>
        <tr>
            <th>Joueur</th>
            <th>Licence</th>
            <th>Taille</th>
            <th>Poids</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="6" style="text-align:center; padding: 30px;">
                <em>Chargement des joueurs en cours...</em>
            </td>
        </tr>
    </tbody>
</table>

<script src="/ftm/vue/joueurs/script.js"></script>

<?php require_once __DIR__ . '/../footer.php'; ?>