<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . '/../modele/JoueurDAO.php';
require_once __DIR__ . '/../modele/RencontreDAO.php';

// Fetch data for dashboard
$joueurDAO = new JoueurDAO();
$rencontreDAO = new RencontreDAO();

$joueurs = $joueurDAO->getJoueurs();
$rencontres = $rencontreDAO->getRencontres();

// Calculate stats
$totalJoueurs = count($joueurs);
$joueursActifs = count(array_filter($joueurs, fn($j) => $j['statut'] === 'Actif'));
$joueursBlessés = count(array_filter($joueurs, fn($j) => $j['statut'] === 'Blessé'));

$totalMatchs = count($rencontres);
$victoires = count(array_filter($rencontres, fn($r) => $r['resultat'] === 'Victoire'));
$defaites = count(array_filter($rencontres, fn($r) => $r['resultat'] === 'Defaite'));
$nuls = count(array_filter($rencontres, fn($r) => $r['resultat'] === 'Nul'));

// Upcoming matches (no result yet)
$matchsAVenir = array_filter($rencontres, fn($r) => $r['resultat'] === null);
$prochainMatch = !empty($matchsAVenir) ? reset($matchsAVenir) : null;

require_once __DIR__ . '/header.php';
?>
<link rel="stylesheet" href="/css/accueil.css">

<div class="dashboard">
    <div class="welcome-section">
        <h1>Bonjour <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h1>
        <p class="subtitle">Voici un aperçu de votre équipe</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon players">👥</div>
            <div class="stat-info">
                <h3><?= $totalJoueurs ?></h3>
                <p>Joueurs</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon active">✓</div>
            <div class="stat-info">
                <h3><?= $joueursActifs ?></h3>
                <p>Actifs</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon injured">🏥</div>
            <div class="stat-info">
                <h3><?= $joueursBlessés ?></h3>
                <p>Blessés</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon matches">⚽</div>
            <div class="stat-info">
                <h3><?= $totalMatchs ?></h3>
                <p>Matchs</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon victories">🏆</div>
            <div class="stat-info">
                <h3><?= $victoires ?></h3>
                <p>Victoires</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon defeats">📊</div>
            <div class="stat-info">
                <h3><?= $nuls ?></h3>
                <p>Nuls</p>
            </div>
        </div>
    </div>

    <h2 class="section-title">Actions Rapides</h2>
    <div class="quick-actions">
        <a href="/controleur/joueur/AjouterJoueur.php" class="action-card">
            <div class="action-icon">+</div>
            <div>
                <h4>Ajouter un joueur</h4>
                <p>Enregistrer un nouveau membre</p>
            </div>
        </a>
        <a href="/controleur/rencontre/ajouterRencontre.php" class="action-card">
            <div class="action-icon">📅</div>
            <div>
                <h4>Planifier un match</h4>
                <p>Créer une nouvelle rencontre</p>
            </div>
        </a>
        <a href="/controleur/joueur/ObtenirTousLesJoueurs.php" class="action-card">
            <div class="action-icon">👥</div>
            <div>
                <h4>Voir l'effectif</h4>
                <p>Gérer les joueurs</p>
            </div>
        </a>
        <a href="/controleur/rencontre/ObtenirToutesLesRencontres.php" class="action-card">
            <div class="action-icon">📋</div>
            <div>
                <h4>Calendrier</h4>
                <p>Voir tous les matchs</p>
            </div>
        </a>
    </div>

    <h2 class="section-title">Aperçu des Matchs</h2>
    <div class="next-match-section">
        <div class="next-match-card">
            <h3>⏱ Prochain Match</h3>
            <?php if ($prochainMatch): ?>
                <?php
                $date = new DateTime($prochainMatch['date_rencontre']);
                $day = $date->format('d');
                $month = $date->format('M');
                ?>
                <div class="match-info">
                    <div class="match-date-box">
                        <div class="day"><?= $day ?></div>
                        <div class="month"><?= $month ?></div>
                    </div>
                    <div class="match-details">
                        <h4>vs <?= htmlspecialchars($prochainMatch['nom_equipe_adverse']) ?></h4>
                        <p><?= htmlspecialchars($prochainMatch['heure']) ?> -
                            <?= htmlspecialchars($prochainMatch['adresse']) ?>
                        </p>
                        <span class="venue-badge"><?= htmlspecialchars($prochainMatch['lieu']) ?></span>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-match">
                    <p>Aucun match programmé</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="recent-results-card">
            <h3>📊 Derniers Résultats</h3>
            <?php
            $matchsJoues = array_filter($rencontres, fn($r) => $r['resultat'] !== null);
            $dernierMatchs = array_slice($matchsJoues, 0, 4);
            ?>
            <?php if (!empty($dernierMatchs)): ?>
                <?php foreach ($dernierMatchs as $m): ?>
                    <div class="result-item">
                        <span class="result-opponent">vs <?= htmlspecialchars($m['nom_equipe_adverse']) ?></span>
                        <?php
                        $resultClass = strtolower($m['resultat']);
                        $resultText = $m['resultat'];
                        ?>
                        <span class="result-badge <?= $resultClass ?>"><?= $resultText ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-match">
                    <p>Aucun résultat disponible</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>