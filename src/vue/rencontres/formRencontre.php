<?php require_once __DIR__ . '/../header.php'; ?>
<!-- utilise ajouterRencontre.php AND ModifierUneRencontre.php -->

<style>
    .form-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 40px 50px;
        max-width: 750px;
        margin: 40px auto;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .form-card h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 8px 0;
    }

    .form-subtitle {
        color: #888;
        font-size: 0.95rem;
        margin-bottom: 30px;
        font-weight: 400;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }

    .form-section h3 {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 12px 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .form-group input[type="text"],
    .form-group input[type="date"],
    .form-group input[type="time"],
    .form-group select {
        width: 100%;
        padding: 14px 16px;
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        font-size: 0.95rem;
        color: #333;
        background-color: #fff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #1db988;
        box-shadow: 0 0 0 3px rgba(29, 185, 136, 0.1);
    }

    .form-group input::placeholder {
        color: #aaa;
    }

    /* Radio buttons styling */
    .radio-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .radio-option {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }

    .radio-option input[type="radio"] {
        appearance: none;
        -webkit-appearance: none;
        width: 20px;
        height: 20px;
        border: 2px solid #ccc;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .radio-option input[type="radio"]:checked {
        border-color: #1db988;
    }

    .radio-option input[type="radio"]:checked::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 10px;
        height: 10px;
        background-color: #1db988;
        border-radius: 50%;
    }

    .radio-option span {
        font-size: 0.95rem;
        color: #555;
    }

    /* Buttons */
    .form-actions {
        display: flex;
        gap: 16px;
        justify-content: center;
        margin-top: 35px;
        padding-top: 20px;
    }

    .btn {
        padding: 14px 40px;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
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

    /* Icon styling for inputs */
    .input-with-icon {
        position: relative;
    }

    .input-with-icon input,
    .input-with-icon select {
        padding-right: 45px;
    }

    .input-with-icon .icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
        font-size: 1.1rem;
        pointer-events: none;
    }

    @media (max-width: 700px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .form-card {
            margin: 20px;
            padding: 30px 25px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>

<div class="form-card">
    <h2><?php echo isset($rencontre) ? 'Modifier le Match' : 'New Match'; ?></h2>
    <p class="form-subtitle">General Information</p>

    <form method="POST" action="<?php echo isset($rencontre) ? 'ModifierUneRencontre.php' : 'ajouterRencontre.php'; ?>">
        <?php if (isset($rencontre)): ?>
            <input type="hidden" name="id_rencontre" value="<?php echo $rencontre['id_rencontre']; ?>">
        <?php endif; ?>

        <div class="form-grid">
            <!-- Left Column - General Information -->
            <div class="form-section">
                <h3>General Information</h3>
                
                <div class="form-group">
                    <label for="nom_equipe_adverse">Opponent Team</label>
                    <div class="input-with-icon">
                        <select name="nom_equipe_adverse" id="nom_equipe_adverse" required>
                            <option value="" disabled <?php echo !isset($rencontre) ? 'selected' : ''; ?>>Select opponent...</option>
                            <option value="<?php echo $rencontre['nom_equipe_adverse'] ?? ''; ?>" <?php echo isset($rencontre) ? 'selected' : ''; ?>><?php echo $rencontre['nom_equipe_adverse'] ?? ''; ?></option>
                        </select>
                        <span class="icon">▲</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="date_rencontre">Date</label>
                    <div class="input-with-icon">
                        <input type="date" name="date_rencontre" id="date_rencontre" 
                               value="<?php echo $rencontre['date_rencontre'] ?? ''; ?>" 
                               placeholder="Ex: FC Paris" required>
                        <span class="icon">📅</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="heure">Time</label>
                    <div class="input-with-icon">
                        <input type="time" name="heure" id="heure" 
                               value="<?php echo $rencontre['heure'] ?? ''; ?>" required>
                        <span class="icon">🕐</span>
                    </div>
                </div>
            </div>

            <!-- Right Column - Location -->
            <div class="form-section">
                <h3>Location</h3>
                
                <div class="form-group">
                    <label>Venue</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="lieu" value="Domicile" 
                                   <?php echo (!isset($rencontre) || $rencontre['lieu'] == 'Domicile') ? 'checked' : ''; ?>>
                            <span>Home</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="lieu" value="Exterieur"
                                   <?php echo (isset($rencontre) && $rencontre['lieu'] == 'Exterieur') ? 'checked' : ''; ?>>
                            <span>Away</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="adresse">Match Address</label>
                    <input type="text" name="adresse" id="adresse" 
                           value="<?php echo $rencontre['adresse'] ?? ''; ?>" 
                           placeholder="Enter full stadium address..." required>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?php echo isset($rencontre) ? 'Update Match' : 'Create Match'; ?>
            </button>
            <a href="/controleur/rencontre/ObtenirToutesLesRencontres.php" class="btn btn-secondary" style="text-decoration: none; text-align: center;">
                Cancel
            </a>
        </div>
    </form>
</div>

</div>
</body>
</html>