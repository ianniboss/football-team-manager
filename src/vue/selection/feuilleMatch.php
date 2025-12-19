<?php require_once __DIR__ . '/../header.php'; ?>
<!-- utilise AfficherSelection.php -->

<style>
    .selection-container {
        max-width: 1000px;
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

    /* Player Table */
    .players-table {
        width: 100%;
        border-collapse: collapse;
    }

    .players-table thead {
        background: #f8f9fa;
    }

    .players-table th {
        padding: 15px 20px;
        text-align: left;
        font-size: 0.85rem;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e0e0e0;
    }

    .players-table td {
        padding: 18px 20px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .players-table tbody tr:hover {
        background: #f8f9fa;
    }

    .players-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Player Cell */
    .player-cell {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .player-avatar {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .player-info .name {
        font-weight: 600;
        color: #1a1a1a;
    }

    .player-info .license {
        font-size: 0.8rem;
        color: #888;
        margin-top: 2px;
    }

    /* Position Input */
    .position-input {
        width: 140px;
        padding: 10px 14px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: border-color 0.2s ease;
    }

    .position-input:focus {
        outline: none;
        border-color: #1db988;
    }

    /* Checkbox Styling */
    .checkbox-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .styled-checkbox {
        width: 22px;
        height: 22px;
        accent-color: #1db988;
        cursor: pointer;
    }

    .titulaire-checkbox {
        accent-color: #28a745;
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
        .players-table {
            display: block;
            overflow-x: auto;
        }

        .position-input {
            width: 100px;
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

    <!-- Instructions -->
    <div class="instructions">
        💡 <strong>Instructions :</strong> Cochez les joueurs à convoquer, indiquez leur poste, et marquez-les comme titulaires si nécessaire.
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
                <table class="players-table">
                    <thead>
                        <tr>
                            <th>Convoquer</th>
                            <th>Joueur</th>
                            <th>Poste</th>
                            <th>Titulaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tousLesJoueurs as $j): 
                            $id = $j['id_joueur'];
                            $is_selected = isset($selectionActuelle[$id]);
                            $val_poste = $is_selected ? $selectionActuelle[$id]['poste'] : '';
                            $is_titulaire = ($is_selected && $selectionActuelle[$id]['titulaire'] == 1);
                        ?>
                            <tr>
                                <td>
                                    <div class="checkbox-wrapper">
                                        <input type="checkbox" 
                                               class="styled-checkbox"
                                               name="joueurs[<?php echo $id; ?>][selected]" 
                                               <?php echo $is_selected ? 'checked' : ''; ?>>
                                    </div>
                                </td>
                                <td>
                                    <div class="player-cell">
                                        <div class="player-avatar">
                                            <?= strtoupper(substr($j['prenom'], 0, 1) . substr($j['nom'], 0, 1)) ?>
                                        </div>
                                        <div class="player-info">
                                            <div class="name"><?php echo htmlspecialchars($j['prenom'] . ' ' . $j['nom']); ?></div>
                                            <div class="license"><?= htmlspecialchars($j['num_licence']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <select name="joueurs[<?php echo $id; ?>][poste]" class="position-input">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="Gardien" <?= $val_poste === 'Gardien' ? 'selected' : '' ?>>Gardien</option>
                                        <option value="Défenseur Central" <?= $val_poste === 'Défenseur Central' ? 'selected' : '' ?>>Défenseur Central</option>
                                        <option value="Défenseur Latéral Droit" <?= $val_poste === 'Défenseur Latéral Droit' ? 'selected' : '' ?>>Défenseur Latéral Droit</option>
                                        <option value="Défenseur Latéral Gauche" <?= $val_poste === 'Défenseur Latéral Gauche' ? 'selected' : '' ?>>Défenseur Latéral Gauche</option>
                                        <option value="Milieu Défensif" <?= $val_poste === 'Milieu Défensif' ? 'selected' : '' ?>>Milieu Défensif</option>
                                        <option value="Milieu Central" <?= $val_poste === 'Milieu Central' ? 'selected' : '' ?>>Milieu Central</option>
                                        <option value="Milieu Offensif" <?= $val_poste === 'Milieu Offensif' ? 'selected' : '' ?>>Milieu Offensif</option>
                                        <option value="Ailier Droit" <?= $val_poste === 'Ailier Droit' ? 'selected' : '' ?>>Ailier Droit</option>
                                        <option value="Ailier Gauche" <?= $val_poste === 'Ailier Gauche' ? 'selected' : '' ?>>Ailier Gauche</option>
                                        <option value="Attaquant" <?= $val_poste === 'Attaquant' ? 'selected' : '' ?>>Attaquant</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="checkbox-wrapper">
                                        <input type="checkbox" 
                                               class="styled-checkbox titulaire-checkbox"
                                               name="joueurs[<?php echo $id; ?>][titulaire]" 
                                               value="1" 
                                               <?php echo $is_titulaire ? 'checked' : ''; ?>>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Submit -->
        <div class="form-actions">
            <button type="submit" class="btn-submit">
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