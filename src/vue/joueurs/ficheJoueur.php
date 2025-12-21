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

    .comments-section {
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #f0f0f0;
    }

    .comments-section h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .comments-section h3::before {
        content: "💬";
    }

    .comment-form {
        margin-bottom: 25px;
    }

    .comment-form textarea {
        width: 100%;
        min-height: 100px;
        padding: 14px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        font-family: inherit;
        resize: vertical;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        box-sizing: border-box;
    }

    .comment-form textarea:focus {
        outline: none;
        border-color: #1db988;
        box-shadow: 0 0 0 3px rgba(29, 185, 136, 0.1);
    }

    .comment-form textarea::placeholder {
        color: #aaa;
    }

    .comment-form .btn-submit {
        margin-top: 12px;
        padding: 12px 30px;
        background-color: #1db988;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .comment-form .btn-submit:hover {
        background-color: #17a077;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(29, 185, 136, 0.3);
    }

    .comments-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .comment-item {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 16px 20px;
        border-left: 4px solid #1db988;
        position: relative;
    }

    .comment-date {
        font-size: 0.8rem;
        color: #888;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .comment-text {
        font-size: 1rem;
        color: #333;
        line-height: 1.5;
        padding-right: 30px;
    }

    .comment-delete {
        position: absolute;
        top: 12px;
        right: 12px;
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        font-size: 1.1rem;
        opacity: 0.6;
        transition: opacity 0.2s ease;
        padding: 4px 8px;
    }

    .comment-delete:hover {
        opacity: 1;
    }

    .no-comments {
        text-align: center;
        padding: 30px;
        color: #888;
        font-style: italic;
        background-color: #f8f9fa;
        border-radius: 10px;
        font-size: 0.9rem;
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

    <div class="comments-section">
        <h3>Commentaires</h3>

        <form class="comment-form" action="/controleur/commentaire/AjouterUnCommentaireAuJoueur.php" method="POST">
            <input type="hidden" name="id_joueur" value="<?php echo $joueur['id_joueur']; ?>">
            <textarea name="commentaire" placeholder="Ajouter une note personnelle sur ce joueur..."
                required></textarea>
            <button type="submit" class="btn-submit">Ajouter le commentaire</button>
        </form>

        <div class="comments-list">
            <?php if (!empty($commentaires)): ?>
                <?php foreach ($commentaires as $commentaire): ?>
                    <div class="comment-item">
                        <div class="comment-date">
                            <?php
                            $date = new DateTime($commentaire['date_commentaire']);
                            echo $date->format('d/m/Y');
                            ?>
                        </div>
                        <div class="comment-text">
                            <?php echo htmlspecialchars($commentaire['commentaire']); ?>
                        </div>
                        <form action="/controleur/commentaire/SupprimerUnCommentaireDuJoueur.php" method="POST"
                            style="display: inline;">
                            <input type="hidden" name="id_commentaire" value="<?php echo $commentaire['id_commentaire']; ?>">
                            <input type="hidden" name="id_joueur" value="<?php echo $joueur['id_joueur']; ?>">
                            <button type="submit" class="comment-delete" title="Supprimer ce commentaire"
                                onclick="return confirm('Voulez-vous vraiment supprimer ce commentaire ?');">
                                ✕
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-comments">
                    Aucun commentaire pour ce joueur. Ajoutez vos premières notes !
                </div>
            <?php endif; ?>
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