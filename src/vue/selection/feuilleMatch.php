<?php require_once __DIR__ . '/../header.php'; ?>
<!-- utilise AfficherSelection.php -->

<style>
    .selection-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #1db988;
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 25px;
        transition: color 0.2s ease;
    }

    .back-link:hover {
        color: #17a077;
    }

    /* Match Header */
    .match-header {
        background: linear-gradient(135deg, #2d3436 0%, #000000 100%);
        border-radius: 16px;
        padding: 30px;
        color: white;
        margin-bottom: 25px;
        text-align: center;
    }

    .match-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 10px 0;
    }

    .match-header p {
        color: rgba(255,255,255,0.7);
        margin: 0;
    }

    /* Instructions */
    .instructions {
        background: #e8f5e9;
        border: 1px solid #a5d6a7;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
        color: #2e7d32;
    }

    /* Error Message */
    .error-message {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
        color: #721c24;
    }

    /* Players Panel */
    .players-panel {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }

    .players-panel h3 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 25px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Player Cards Grid */
    .players-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 20px;
    }

    .player-card {
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.2s ease;
    }

    .player-card:hover {
        border-color: #1db988;
        box-shadow: 0 4px 15px rgba(29, 185, 136, 0.15);
    }

    .player-card.selected {
        border-color: #1db988;
        background: #f0fdf9;
    }

    /* Player Header */
    .player-card-header {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 15px;
    }

    .player-photo {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        overflow: hidden;
        flex-shrink: 0;
        border: 2px solid #e0e0e0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .player-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .player-photo-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.3rem;
    }

    .player-main-info {
        flex: 1;
    }

    .player-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .player-license {
        font-size: 0.8rem;
        color: #888;
        margin-bottom: 8px;
    }

    .player-physical {
        display: flex;
        gap: 15px;
    }

    .physical-stat {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.85rem;
        color: #555;
        background: white;
        padding: 4px 10px;
        border-radius: 15px;
        border: 1px solid #e0e0e0;
    }

    .physical-stat strong {
        color: #1a1a1a;
    }

    /* Player Stats Row */
    .player-stats-row {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .stat-badge.matches {
        background: #e3f2fd;
        color: #1976d2;
    }

    .stat-badge.rating {
        background: #fff3e0;
        color: #e65100;
    }

    .stat-badge.no-data {
        background: #f5f5f5;
        color: #888;
    }

    /* Comments History */
    .player-comments {
        background: white;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #e8e8e8;
    }

    .comments-title {
        font-size: 0.75rem;
        font-weight: 600;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .comment-mini {
        font-size: 0.85rem;
        color: #555;
        padding: 6px 0;
        border-bottom: 1px dashed #eee;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
    }

    .comment-mini:last-child {
        border-bottom: none;
    }

    .comment-mini-text {
        flex: 1;
        line-height: 1.4;
    }

    .comment-mini-date {
        font-size: 0.7rem;
        color: #aaa;
        white-space: nowrap;
    }

    .no-comments {
        font-size: 0.85rem;
        color: #aaa;
        font-style: italic;
    }

    /* Selection Controls */
    .selection-controls {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-top: 15px;
        border-top: 1px solid #e0e0e0;
        flex-wrap: wrap;
    }

    .control-group {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    .control-group label {
        font-size: 0.8rem;
        font-weight: 500;
        color: #555;
    }

    .styled-checkbox {
        width: 20px;
        height: 20px;
        accent-color: #1db988;
        cursor: pointer;
    }

    .titulaire-checkbox {
        accent-color: #28a745;
    }

    .position-input {
        padding: 8px 10px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.8rem;
        flex: 1;
        min-width: 120px;
        max-width: 100%;
        transition: border-color 0.2s ease;
    }

    .position-input:focus {
        outline: none;
        border-color: #1db988;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 15px 40px;
        background: linear-gradient(135deg, #1db988 0%, #17a077 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(29, 185, 136, 0.4);
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 15px 30px;
        background: #f5f5f5;
        color: #333;
        border: 2px solid #ddd;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: #eee;
        border-color: #ccc;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #888;
    }

    @media (max-width: 768px) {
        .players-grid {
            grid-template-columns: 1fr;
        }

        .selection-controls {
            flex-wrap: wrap;
        }
    }
</style>

<div class="selection-container">
    <a href="/controleur/rencontre/RechercherUneRencontre.php?id=<?php echo $rencontre['id_rencontre']; ?>" class="back-link">
        ← Retour aux détails du match
    </a>

    <!-- Match Header -->
    <div class="match-header">
        <h1>📋 Feuille de Match</h1>
        <p>Sélection pour le match contre <?php echo htmlspecialchars($rencontre['nom_equipe_adverse']); ?> - <?php echo $rencontre['date_rencontre']; ?></p>
    </div>

    <!-- Error Message (if validation failed) -->
    <?php if (isset($_GET['error']) && $_GET['error'] === 'min_titulaires'): ?>
        <div class="error-message">
            ⚠️ <strong>Erreur :</strong> Vous devez sélectionner au moins <strong>11 titulaires</strong> pour valider la feuille de match. 
            Actuellement : <?php echo isset($_GET['count']) ? intval($_GET['count']) : 0; ?> titulaire(s).
        </div>
    <?php endif; ?>

    <!-- Instructions -->
    <div class="instructions">
        💡 <strong>Instructions :</strong> Cochez les joueurs à convoquer, indiquez leur poste, et marquez-les comme titulaires (minimum 11 requis). Consultez leurs statistiques et commentaires pour faire votre choix.
    </div>

    <form method="POST" action="EnregistrerSelection.php">
        <input type="hidden" name="id_rencontre" value="<?php echo $rencontre['id_rencontre']; ?>">

        <!-- Players Panel -->
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
                        
                        // Check if we have pending selection data (from failed validation)
                        if ($pendingSelection !== null) {
                            // Use pending data from the failed form submission
                            $is_selected = isset($pendingSelection[$id]['selected']);
                            $val_poste = isset($pendingSelection[$id]['poste']) ? $pendingSelection[$id]['poste'] : '';
                            $is_titulaire = isset($pendingSelection[$id]['titulaire']);
                        } else {
                            // Use database data
                            $is_selected = isset($selectionActuelle[$id]);
                            $val_poste = $is_selected ? $selectionActuelle[$id]['poste'] : '';
                            $is_titulaire = ($is_selected && $selectionActuelle[$id]['titulaire'] == 1);
                        }
                        
                        $hasImage = !empty($j['image']);
                        $comments = isset($joueursCommentaires[$id]) ? $joueursCommentaires[$id] : [];
                        $stats = isset($joueursStats[$id]) ? $joueursStats[$id] : null;
                    ?>
                        <div class="player-card <?php echo $is_selected ? 'selected' : ''; ?>">
                            <!-- Player Header with Photo -->
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

                            <!-- Player Stats -->
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

                            <!-- Comments History -->
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

                            <!-- Selection Controls -->
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

        <!-- Submit -->
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

</div>

</body>

</html>