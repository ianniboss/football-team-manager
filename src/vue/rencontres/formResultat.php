<?php require_once __DIR__ . '/../header.php'; ?>
<!-- utilise SaisirResultatEtEvaluations.php -->

<style>
    .result-form-container {
        max-width: 800px;
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
    .match-info-header {
        background: linear-gradient(135deg, #2d3436 0%, #000000 100%);
        border-radius: 16px;
        padding: 30px;
        color: white;
        margin-bottom: 25px;
        text-align: center;
    }

    .match-info-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 10px 0;
    }

    .match-info-header p {
        color: rgba(255,255,255,0.7);
        margin: 0;
    }

    /* Result Selection Card */
    .result-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }

    .result-card h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .result-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .result-option {
        position: relative;
    }

    .result-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
    }

    .result-option label {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 25px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: white;
    }

    .result-option label .icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .result-option label .text {
        font-weight: 600;
        color: #333;
    }

    .result-option input:checked + label {
        border-color: #1db988;
        background-color: #f0fdf9;
    }

    .result-option.victoire input:checked + label {
        border-color: #28a745;
        background-color: #d4edda;
    }

    .result-option.defaite input:checked + label {
        border-color: #dc3545;
        background-color: #f8d7da;
    }

    .result-option.nul input:checked + label {
        border-color: #6c757d;
        background-color: #e2e3e5;
    }

    /* Evaluations Card */
    .evaluations-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }

    .evaluations-card h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .player-eval-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .player-eval-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: #f8f9fa;
        border-radius: 10px;
        transition: background 0.2s ease;
    }

    .player-eval-row:hover {
        background: #f0f0f0;
    }

    .player-info {
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
    }

    .player-name {
        font-weight: 600;
        color: #1a1a1a;
    }

    .player-role {
        font-size: 0.85rem;
        color: #888;
    }

    /* Star Rating */
    .star-rating {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .star-rating input[type="number"] {
        width: 70px;
        padding: 10px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        text-align: center;
        transition: border-color 0.2s ease;
    }

    .star-rating input[type="number"]:focus {
        outline: none;
        border-color: #1db988;
    }

    .star-rating span {
        color: #f39c12;
        font-size: 1.2rem;
    }

    /* Submit Button */
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

    @media (max-width: 600px) {
        .result-options {
            grid-template-columns: 1fr;
        }

        .player-eval-row {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .player-info {
            flex-direction: column;
        }
    }
</style>

<div class="result-form-container">
    <a href="/controleur/rencontre/RechercherUneRencontre.php?id=<?php echo $rencontre['id_rencontre']; ?>" class="back-link">
        ← Retour aux détails
    </a>

    <!-- Match Header -->
    <div class="match-info-header">
        <h1>📊 Résultat & Évaluations</h1>
        <p>Match contre <?php echo htmlspecialchars($rencontre['nom_equipe_adverse']); ?> - <?php echo $rencontre['date_rencontre']; ?></p>
    </div>

    <form method="POST" action="SaisirResultatEtEvaluations.php">
        <input type="hidden" name="id_rencontre" value="<?php echo $rencontre['id_rencontre']; ?>">

        <!-- Result Selection -->
        <div class="result-card">
            <h3>🏆 Résultat du match</h3>
            <div class="result-options">
                <div class="result-option victoire">
                    <input type="radio" name="resultat" id="victoire" value="Victoire" 
                           <?php echo ($rencontre['resultat'] == 'Victoire') ? 'checked' : ''; ?>>
                    <label for="victoire">
                        <span class="icon">🎉</span>
                        <span class="text">Victoire</span>
                    </label>
                </div>
                <div class="result-option nul">
                    <input type="radio" name="resultat" id="nul" value="Nul"
                           <?php echo ($rencontre['resultat'] == 'Nul') ? 'checked' : ''; ?>>
                    <label for="nul">
                        <span class="icon">🤝</span>
                        <span class="text">Match Nul</span>
                    </label>
                </div>
                <div class="result-option defaite">
                    <input type="radio" name="resultat" id="defaite" value="Defaite"
                           <?php echo ($rencontre['resultat'] == 'Defaite') ? 'checked' : ''; ?>>
                    <label for="defaite">
                        <span class="icon">😔</span>
                        <span class="text">Défaite</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Player Evaluations -->
        <div class="evaluations-card">
            <h3>⭐ Évaluer les joueurs</h3>
            
            <?php if (empty($joueursFeuille)): ?>
                <div class="empty-state">
                    <p>Aucun joueur n'a été convoqué pour ce match.</p>
                </div>
            <?php else: ?>
                <div class="player-eval-list">
                    <?php foreach ($joueursFeuille as $j): ?>
                        <div class="player-eval-row">
                            <div class="player-info">
                                <?php if (!empty($j['image'])): ?>
                                    <img src="/modele/img/players/<?php echo htmlspecialchars($j['image']); ?>" 
                                         alt="Photo de <?php echo htmlspecialchars($j['prenom']); ?>"
                                         style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #e0e0e0;">
                                <?php else: ?>
                                    <div class="player-avatar">
                                        <?= strtoupper(substr($j['prenom'], 0, 1) . substr($j['nom'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="player-name"><?php echo htmlspecialchars($j['prenom'] . ' ' . $j['nom']); ?></div>
                                    <div class="player-role"><?= $j['titulaire'] ? 'Titulaire' : 'Remplaçant' ?></div>
                                </div>
                            </div>
                            <div class="star-rating">
                                <input type="number" name="evaluations[<?php echo $j['id_participation']; ?>]"
                                       value="<?php echo $j['evaluation']; ?>" min="1" max="5" placeholder="-">
                                <span>/ 5 ⭐</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Submit -->
        <div class="form-actions">
            <button type="submit" class="btn-submit">
                ✓ Enregistrer le résultat
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