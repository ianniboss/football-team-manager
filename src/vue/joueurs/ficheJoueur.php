<?php require_once __DIR__ . '/../header.php'; ?>
<!-- Fiche détaillée du joueur - Utilisé par ObtenirUnJoueur.php -->

<style>
    .player-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 40px 50px;
        max-width: 750px;
        margin: 40px auto;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        position: relative;
    }

    .player-image {
        position: absolute;
        top: 25px;
        right: 25px;
        width: 130px;
        height: 130px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        border: 3px solid #1db988;
        background-color: #f0f0f0;
    }

    .player-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .player-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #e0e0e0 0%, #f5f5f5 100%);
        color: #999;
        font-size: 3rem;
    }

    .player-card h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 8px 0;
    }

    .player-subtitle {
        color: #888;
        font-size: 0.95rem;
        margin-bottom: 30px;
        font-weight: 400;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }

    .info-section h3 {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 20px 0;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }

    .info-item {
        margin-bottom: 20px;
    }

    .info-item label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .info-item .value {
        font-size: 1.1rem;
        font-weight: 500;
        color: #333;
        padding: 12px 16px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border-left: 3px solid #1db988;
    }

    .info-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    /* Status badge */
    .status-badge {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 0.9rem;
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

    /* Buttons */
    .card-actions {
        display: flex;
        gap: 16px;
        justify-content: center;
        margin-top: 35px;
        padding-top: 25px;
        border-top: 1px solid #eee;
    }

    .btn {
        padding: 14px 40px;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        text-decoration: none;
        text-align: center;
    }

    .btn-primary {
        background-color: #1db988;
        color: white;
    }

    .btn-primary:hover {
        background-color: #17a077;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(29, 185, 136, 0.3);
    }

    .btn-secondary {
        background-color: #f5f5f5;
        color: #333;
        border: 1.5px solid #ddd;
    }

    .btn-secondary:hover {
        background-color: #eee;
        border-color: #ccc;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    @media (max-width: 700px) {
        .player-image {
            position: static;
            width: 100%;
            height: 200px;
            margin-bottom: 20px;
        }

        .info-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .player-card {
            margin: 20px;
            padding: 30px 25px;
        }

        .card-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }

        .info-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php
$hasImage = !empty($joueur['image']);
$imagePath = $hasImage ? '/modele/img/players/' . htmlspecialchars($joueur['image']) : '';
?>

<div class="player-card">
    <div class="player-image">
        <?php if ($hasImage): ?>
            <img src="<?php echo $imagePath; ?>"
                alt="Photo de <?php echo htmlspecialchars($joueur['prenom'] . ' ' . $joueur['nom']); ?>">
        <?php else: ?>
            <div class="player-image-placeholder">
                <?php echo strtoupper(substr($joueur['prenom'], 0, 1) . substr($joueur['nom'], 0, 1)); ?>
            </div>
        <?php endif; ?>
    </div>

    <h2><?php echo htmlspecialchars($joueur['prenom'] . ' ' . $joueur['nom']); ?></h2>
    <p class="player-subtitle">Fiche du joueur</p>

    <div class="info-grid">
        <!-- Left Column - Informations personnelles -->
        <div class="info-section">
            <h3>Informations personnelles</h3>

            <div class="info-item">
                <label>Prénom</label>
                <div class="value"><?php echo htmlspecialchars($joueur['prenom']); ?></div>
            </div>

            <div class="info-item">
                <label>Nom</label>
                <div class="value"><?php echo htmlspecialchars($joueur['nom']); ?></div>
            </div>

            <div class="info-item">
                <label>Date de naissance</label>
                <div class="value"><?php echo htmlspecialchars($joueur['date_naissance']); ?></div>
            </div>

            <div class="info-item">
                <label>Numéro de licence</label>
                <div class="value"><?php echo htmlspecialchars($joueur['num_licence']); ?></div>
            </div>
        </div>

        <!-- Right Column - Attributs physiques & Statut -->
        <div class="info-section">
            <h3>Attributs physiques</h3>

            <div class="info-item">
                <label>Taille & Poids</label>
                <div class="info-row">
                    <div class="value"><?php echo htmlspecialchars($joueur['taille']); ?> cm</div>
                    <div class="value"><?php echo htmlspecialchars($joueur['poids']); ?> kg</div>
                </div>
            </div>

            <div class="info-item" style="margin-top: 30px;">
                <label>Statut</label>
                <?php
                $statut = $joueur['statut'];
                $statusClass = 'status-actif';
                if ($statut == 'Blessé')
                    $statusClass = 'status-blesse';
                else if ($statut == 'Suspendu')
                    $statusClass = 'status-suspendu';
                else if ($statut == 'Absent')
                    $statusClass = 'status-absent';
                ?>
                <div class="status-badge <?php echo $statusClass; ?>">
                    <?php echo htmlspecialchars($statut); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card-actions">
        <a href="/controleur/joueur/ModifierIdentiteDuJoueur.php?id=<?php echo $joueur['id_joueur']; ?>"
            class="btn btn-primary">
            Modifier
        </a>
        <a href="/controleur/joueur/ObtenirTousLesJoueurs.php" class="btn btn-secondary">
            Retour à la liste
        </a>
    </div>
</div>

</div>
</body>

</html>