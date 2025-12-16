<?php
// Session already started by controller (AfficherStatistiques.php)
require_once __DIR__ . '/../header.php';
?>
<h2>Statistiques Globales</h2>

<div style="display: flex; gap: 20px; margin-bottom: 30px;">
    <div style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; flex: 1; background-color: #d4edda;">
        <h3>Victoires</h3>
        <p style="font-size: 24px; font-weight: bold;">
            <?php echo $statsGlobales['victoires']; ?>
            <small>(<?php echo $statsGlobales['pct_victoires']; ?>%)</small>
        </p>
    </div>
    <div style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; flex: 1; background-color: #f8d7da;">
        <h3>Défaites</h3>
        <p style="font-size: 24px; font-weight: bold;">
            <?php echo $statsGlobales['defaites']; ?>
            <small>(<?php echo $statsGlobales['pct_defaites']; ?>%)</small>
        </p>
    </div>
    <div style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; flex: 1; background-color: #fff3cd;">
        <h3>Nuls</h3>
        <p style="font-size: 24px; font-weight: bold;">
            <?php echo $statsGlobales['nuls']; ?>
            <small>(<?php echo $statsGlobales['pct_nuls']; ?>%)</small>
        </p>
    </div>
    <div style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; flex: 1;">
        <h3>Total Matchs</h3>
        <p style="font-size: 24px; font-weight: bold;"><?php echo $statsGlobales['total_joues']; ?></p>
    </div>
</div>

<h2>Performances par Joueur</h2>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>Joueur</th>
            <th>Statut</th>
            <th>Poste Préféré</th>
            <th>Titularisations</th>
            <th>Remplaçant</th>
            <th>Note Moy.</th>
            <th>% Victoires</th>
            <th>Série en cours</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tableauJoueurs as $j): ?>
            <tr>
                <td><?php echo htmlspecialchars($j['nom'] . ' ' . $j['prenom']); ?></td>
                <td><?php echo htmlspecialchars($j['statut']); ?></td>
                <td><?php echo htmlspecialchars($j['poste_prefere']); ?></td>
                <td align="center"><?php echo $j['titularisations']; ?></td>
                <td align="center"><?php echo $j['remplacements']; ?></td>
                <td align="center"><strong><?php echo $j['moyenne_notes']; ?></strong></td>
                <td align="center"><?php echo $j['pct_gagne']; ?>%</td>
                <td align="center"><?php echo $j['serie_cours']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>

</html>