<?php require_once __DIR__ . '/../header.php'; ?>
        <h2>Sélection pour le match vs <?php echo htmlspecialchars($rencontre['nom_equipe_adverse']); ?></h2>
        
        <form method="POST" action="EnregistrerSelection.php">
            <input type="hidden" name="id_rencontre" value="<?php echo $rencontre['id_rencontre']; ?>">

            <table border="1">
                <thead>
                    <tr>
                        <th>Sélectionner</th>
                        <th>Joueur</th>
                        <th>Poste</th>
                        <th>Titulaire ?</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tousLesJoueurs as $j): 
                        $id = $j['id_joueur'];
                        // Check if player is already in DB for this match
                        $is_selected = isset($selectionActuelle[$id]);
                        $val_poste = $is_selected ? $selectionActuelle[$id]['poste'] : '';
                        $is_titulaire = ($is_selected && $selectionActuelle[$id]['titulaire'] == 1);
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="joueurs[<?php echo $id; ?>][selected]" 
                                   <?php echo $is_selected ? 'checked' : ''; ?>>
                        </td>
                        <td><?php echo htmlspecialchars($j['nom'] . ' ' . $j['prenom']); ?></td>
                        <td>
                            <input type="text" name="joueurs[<?php echo $id; ?>][poste]" 
                                   value="<?php echo htmlspecialchars($val_poste); ?>" placeholder="Ex: Attaquant">
                        </td>
                        <td>
                            <input type="checkbox" name="joueurs[<?php echo $id; ?>][titulaire]" value="1" 
                                   <?php echo $is_titulaire ? 'checked' : ''; ?>>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <button type="submit">Valider la sélection</button>
        </form>
    </div>
</body>
</html>