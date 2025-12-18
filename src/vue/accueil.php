<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: connexion.html");
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

<style>
    .dashboard {
        max-width: 1200px;
        margin: 0 auto;
    }

    .welcome-section {
        margin-bottom: 30px;
    }

    .welcome-section h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .welcome-section .subtitle {
        color: #888;
        font-size: 1rem;
        margin-top: 5px;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.players {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stat-icon.active {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .stat-icon.injured {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .stat-icon.matches {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .stat-icon.victories {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .stat-icon.defeats {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .stat-info h3 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .stat-info p {
        font-size: 0.9rem;
        color: #888;
        margin: 0;
    }

    /* Quick Actions */
    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 20px;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .action-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .action-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        background-color: #1db988;
        color: white;
    }

    .action-card h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }

    .action-card p {
        font-size: 0.85rem;
        color: #888;
        margin: 5px 0 0 0;
    }

    /* Next Match Card */
    .next-match-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .next-match-card {
        background: linear-gradient(135deg, #2d3436 0%, #000000 100%);
        border-radius: 16px;
        padding: 30px;
        color: white;
    }

    .next-match-card h3 {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 20px;
    }

    .match-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .match-date-box {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 15px 20px;
        text-align: center;
    }

    .match-date-box .day {
        font-size: 2rem;
        font-weight: 700;
    }

    .match-date-box .month {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .match-details h4 {
        font-size: 1.3rem;
        font-weight: 600;
        margin: 0 0 8px 0;
    }

    .match-details p {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.7);
        margin: 0;
    }

    .match-details .venue-badge {
        display: inline-block;
        margin-top: 10px;
        padding: 5px 12px;
        background: #1db988;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* Recent Results */
    .recent-results-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .recent-results-card h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 20px;
    }

    .result-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .result-item:last-child {
        border-bottom: none;
    }

    .result-opponent {
        font-weight: 500;
        color: #333;
    }

    .result-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .result-badge.victoire {
        background: #d4edda;
        color: #155724;
    }

    .result-badge.defaite {
        background: #f8d7da;
        color: #721c24;
    }

    .result-badge.nul {
        background: #e2e3e5;
        color: #383d41;
    }

    .no-match {
        text-align: center;
        padding: 30px;
        color: #888;
    }

    @media (max-width: 768px) {
        .next-match-section {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="dashboard">
    <div class="welcome-section">
        <h1>Bonjour <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h1>
        <p class="subtitle">Voici un aperçu de votre équipe</p>
    </div>

    <!-- Stats Grid -->
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

    <!-- Quick Actions -->
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

    <!-- Next Match & Recent Results -->
    <h2 class="section-title">Aperçu des Matchs</h2>
    <div class="next-match-section">
        <!-- Next Match -->
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
                            <?= htmlspecialchars($prochainMatch['adresse']) ?></p>
                        <span class="venue-badge"><?= htmlspecialchars($prochainMatch['lieu']) ?></span>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-match">
                    <p>Aucun match programmé</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Results -->
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

</div>
</body>

</html>