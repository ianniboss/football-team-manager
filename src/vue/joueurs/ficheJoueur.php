<?php
require_once __DIR__ . '/../header.php';

$joueur = $_SESSION['joueur_detail'] ?? null;
$commentaires = $_SESSION['joueur_commentaires'] ?? [];

if (!$joueur) {
    header("Location: /ftm/vue/index.php");
    exit;
}
?>
<!-- Fiche détaillée du joueur - Utilisé par ObtenirUnJoueur.php -->
<link rel="stylesheet" href="/ftm/css/joueurs.css">

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

        <form class="comment-form" action="/api/commentaire/AjouterUnCommentaireAuJoueur.php" method="POST">
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
                        <form action="/api/commentaire/SupprimerUnCommentaireDuJoueur.php" method="POST"
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
        <a href="/api/joueur/ModifierIdentiteDuJoueur.php?id=<?php echo $joueur['id_joueur']; ?>"
            class="btn btn-primary">
            Modifier
        </a>
        <a href="/api/joueur/ObtenirTousLesJoueurs.php" class="btn btn-secondary">
            Retour à la liste
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>