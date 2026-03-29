<?php
require_once __DIR__ . '/../header.php';

$rencontre = $_SESSION['rencontre_selection'] ?? null;
$tousLesJoueurs = $_SESSION['tous_les_joueurs_selection'] ?? [];
$selectionActuelle = $_SESSION['selection_actuelle'] ?? [];
$pendingSelection = $_SESSION['pending_selection_data'] ?? null;
$joueursCommentaires = $_SESSION['joueurs_commentaires_selection'] ?? [];
$joueursStats = $_SESSION['joueurs_stats_selection'] ?? [];

if (!$rencontre) {
    header("Location: /ftm/vue/rencontres/listeRencontres.php");
    exit;
}
?>
<!-- utilise AfficherSelection.php -->
<link rel="stylesheet" href="/ftm/css/selection.css">
<link rel="stylesheet" href="/ftm/css/rencontres.css">
<link rel="stylesheet" href="/ftm/css/forms.css">

<div class="selection-container">
    <a href="/controleur/rencontre/RechercherUneRencontre.php?id=<?php echo $rencontre['id_rencontre']; ?>" class="back-link">
        ← Retour aux détails du match
    </a>

    <div class="match-header">
        <h1>📋 Feuille de Match</h1>
        <p>Sélection pour le match contre <?php echo htmlspecialchars($rencontre['nom_equipe_adverse']); ?> - <?php echo $rencontre['date_rencontre']; ?></p>
    </div>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'min_titulaires'): ?>
        <div class="error-message">
            ⚠️ <strong>Erreur :</strong> Vous devez sélectionner au moins <strong>11 titulaires</strong> pour valider la feuille de match.
            Actuellement : <?php echo isset($_GET['count']) ? intval($_GET['count']) : 0; ?> titulaire(s).
        </div>
    <?php endif; ?>

    <div class="instructions">
        💡 <strong>Instructions :</strong> Cochez les joueurs à convoquer, indiquez leur poste, et marquez-les comme titulaires (minimum 11 requis). Consultez leurs statistiques et commentaires pour faire votre choix.
    </div>

    <form method="POST" action="/controleur/selection/EnregistrerSelection.php">
        <input type="hidden" name="id_rencontre" value="<?php echo $rencontre['id_rencontre']; ?>">

        <div class="players-panel">
            <h3>👥 Effectif disponible</h3>

            <?php if (empty($tousLesJoueurs)): ?>
                <div class="empty-state">
                    <p>Aucun joueur actif disponible.</p>
                </div>
            <?php else: ?>
                <div class="players-grid">
                    <?php foreach ($tousLesJoueurs as $j):
                        $id = $j['id_joueur'];

                        if ($pendingSelection !== null) {
                            $is_selected = isset($pendingSelection[$id]['selected']);
                            $val_poste = isset($pendingSelection[$id]['poste']) ? $pendingSelection[$id]['poste'] : '';
                            $is_titulaire = isset($pendingSelection[$id]['titulaire']);
                        } else {
                            $is_selected = isset($selectionActuelle[$id]);
                            $val_poste = $is_selected ? $selectionActuelle[$id]['poste'] : '';
                            $is_titulaire = ($is_selected && $selectionActuelle[$id]['titulaire'] == 1);
                        }

                        $hasImage = !empty($j['image']);
                        $comments = isset($joueursCommentaires[$id]) ? $joueursCommentaires[$id] : [];
                        $stats = isset($joueursStats[$id]) ? $joueursStats[$id] : null;
                    ?>
                        <div class="player-card <?php echo $is_selected ? 'selected' : ''; ?>">
                            <div class="player-card-header">
                                <div class="player-photo">
                                    <?php if ($hasImage): ?>
                                        <img src="/modele/img/players/<?php echo htmlspecialchars($j['image']); ?>"
                                            alt="Photo de <?php echo htmlspecialchars($j['prenom']); ?>">
                                    <?php else: ?>
                                        <div class="player-photo-placeholder">
                                            <?php echo strtoupper(substr($j['prenom'], 0, 1) . substr($j['nom'], 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="player-main-info">
                                    <div class="player-name"><?php echo htmlspecialchars($j['prenom'] . ' ' . $j['nom']); ?></div>
                                    <div class="player-license"><?php echo htmlspecialchars($j['num_licence']); ?></div>
                                    <div class="player-physical">
                                        <span class="physical-stat">📏 <strong><?php echo htmlspecialchars($j['taille']); ?></strong> cm</span>
                                        <span class="physical-stat">⚖️ <strong><?php echo htmlspecialchars($j['poids']); ?></strong> kg</span>
                                    </div>
                                </div>
                            </div>

                            <div class="player-stats-row">
                                <?php if ($stats && $stats['total_matchs'] > 0): ?>
                                    <span class="stat-badge matches">
                                        ⚽ <?php echo $stats['total_matchs']; ?> matchs
                                    </span>
                                    <span class="stat-badge matches">
                                        🏆 <?php echo $stats['nb_titularisations'] ?? 0; ?> titulaire
                                    </span>
                                    <?php if ($stats['moyenne_notes'] && $stats['moyenne_notes'] > 0): ?>
                                        <span class="stat-badge rating">
                                            ⭐ <?php echo number_format($stats['moyenne_notes'], 1); ?>/5
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="stat-badge no-data">Aucun match joué</span>
                                <?php endif; ?>
                            </div>

                            <div class="player-comments">
                                <div class="comments-title">💬 Commentaires récents</div>
                                <?php if (!empty($comments)): ?>
                                    <?php foreach ($comments as $comment): ?>
                                        <div class="comment-mini">
                                            <span class="comment-mini-text"><?php echo htmlspecialchars(mb_substr($comment['commentaire'], 0, 80)) . (mb_strlen($comment['commentaire']) > 80 ? '...' : ''); ?></span>
                                            <span class="comment-mini-date"><?php echo date('d/m/Y', strtotime($comment['date_commentaire'])); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="no-comments">Aucun commentaire</span>
                                <?php endif; ?>
                            </div>

                            <div class="selection-controls">
                                <div class="control-group">
                                    <input type="checkbox"
                                        class="styled-checkbox"
                                        id="select_<?php echo $id; ?>"
                                        name="joueurs[<?php echo $id; ?>][selected]"
                                        <?php echo $is_selected ? 'checked' : ''; ?>>
                                    <label for="select_<?php echo $id; ?>">Convoquer</label>
                                </div>

                                <div class="control-group">
                                    <input type="checkbox"
                                        class="styled-checkbox titulaire-checkbox"
                                        id="titulaire_<?php echo $id; ?>"
                                        name="joueurs[<?php echo $id; ?>][titulaire]"
                                        value="1"
                                        <?php echo $is_titulaire ? 'checked' : ''; ?>>
                                    <label for="titulaire_<?php echo $id; ?>">Titulaire</label>
                                </div>

                                <select name="joueurs[<?php echo $id; ?>][poste]" class="position-input">
                                    <option value="">-- Poste --</option>
                                    <option value="Gardien" <?= $val_poste === 'Gardien' ? 'selected' : '' ?>>Gardien</option>
                                    <option value="Défenseur Central" <?= $val_poste === 'Défenseur Central' ? 'selected' : '' ?>>Défenseur Central</option>
                                    <option value="Défenseur Latéral Droit" <?= $val_poste === 'Défenseur Latéral Droit' ? 'selected' : '' ?>>Déf. Latéral D</option>
                                    <option value="Défenseur Latéral Gauche" <?= $val_poste === 'Défenseur Latéral Gauche' ? 'selected' : '' ?>>Déf. Latéral G</option>
                                    <option value="Milieu Défensif" <?= $val_poste === 'Milieu Défensif' ? 'selected' : '' ?>>Milieu Défensif</option>
                                    <option value="Milieu Central" <?= $val_poste === 'Milieu Central' ? 'selected' : '' ?>>Milieu Central</option>
                                    <option value="Milieu Offensif" <?= $val_poste === 'Milieu Offensif' ? 'selected' : '' ?>>Milieu Offensif</option>
                                    <option value="Ailier Droit" <?= $val_poste === 'Ailier Droit' ? 'selected' : '' ?>>Ailier Droit</option>
                                    <option value="Ailier Gauche" <?= $val_poste === 'Ailier Gauche' ? 'selected' : '' ?>>Ailier Gauche</option>
                                    <option value="Attaquant" <?= $val_poste === 'Attaquant' ? 'selected' : '' ?>>Attaquant</option>
                                </select>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit" id="submitBtn">
                ✓ Valider la sélection
            </button>
            <a href="/controleur/rencontre/RechercherUneRencontre.php?id=<?php echo $rencontre['id_rencontre']; ?>" class="btn-cancel">
                Annuler
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>