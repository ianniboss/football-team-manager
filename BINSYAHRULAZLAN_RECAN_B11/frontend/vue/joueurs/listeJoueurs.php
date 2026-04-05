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
        <div class="stat-number" id="statTotal">...</div>
        <div class="stat-label">Total Joueurs</div>
    </div>
    <div class="stat-item">
        <div class="stat-number" id="statActifs">...</div>
        <div class="stat-label">Joueurs Actifs</div>
    </div>
    <div class="stat-item">
        <div class="stat-number" id="statBlesses">...</div>
        <div class="stat-label">Joueurs Blessés</div>
    </div>
</div>

<div class="search-section">
    <form id="filterForm" class="search-form">
        <div class="search-input-group">
            <span class="search-icon">🔍</span>
            <input type="text" name="search" id="searchInput" placeholder="Rechercher un joueur...">
        </div>
        <select name="statut" id="statusFilter" class="filter-select">
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
<script>
    // Gestion des filtres en JS
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const search = document.getElementById('searchInput').value;
        const statut = document.getElementById('statusFilter').value;
        getAllJoueurs(search, statut);
    });
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>