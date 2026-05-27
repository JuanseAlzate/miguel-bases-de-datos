<?php

require_once "../config/database.php";

$db = new Database();
$conn = $db->connect();

/*
    CSS
*/
$pageStyles = [
    "player_detail.css"
];

/*
    Parámetro
*/
$playerId = $_GET["id"] ?? null;

if (!$playerId) {
    die("Jugador no encontrado");
}

/*
    Jugador
*/
$sqlPlayer = "
    SELECT 
        p.*,

        t.name AS team_name,
        t.logo_url

    FROM players p

    INNER JOIN teams t
        ON p.team_id = t.id

    WHERE p.id = :id
";

$stmtPlayer = $conn->prepare($sqlPlayer);

$stmtPlayer->bindParam(":id", $playerId);

$stmtPlayer->execute();

$player = $stmtPlayer->fetch();

if (!$player) {
    die("Jugador no encontrado");
}

/*
    Estadísticas
*/
$sqlStats = "
    SELECT 
        SUM(CASE WHEN type = 'GOAL' THEN 1 ELSE 0 END) AS goals,
        SUM(CASE WHEN type = 'ASSIST' THEN 1 ELSE 0 END) AS assists,
        SUM(CASE WHEN type = 'YELLOW_CARD' THEN 1 ELSE 0 END) AS yellow_cards,
        SUM(CASE WHEN type = 'RED_CARD' THEN 1 ELSE 0 END) AS red_cards

    FROM events

    WHERE player_id = :id
";

$stmtStats = $conn->prepare($sqlStats);

$stmtStats->bindParam(":id", $playerId);

$stmtStats->execute();

$stats = $stmtStats->fetch();

/*
    Edad
*/
$birthDate = new DateTime($player["birth_date"]);
$today = new DateTime();

$age = $today->diff($birthDate)->y;

/*
    Header
*/
require_once "../includes/header.php";

?>

<main class="container">

    <!-- HERO -->
    <section class="player-hero">

        <div class="player-hero-card">

            <!-- TEAM -->
            <div class="player-team">

                <img
                    src="../<?php echo $player["logo_url"]; ?>"
                    alt="<?php echo $player["team_name"]; ?>"
                    class="player-team-logo"
                >

                <span>
                    <?php echo $player["team_name"]; ?>
                </span>

            </div>

            <!-- PLAYER -->
            <div class="player-main-info">

                <div class="player-number">

                    <?php echo $player["shirt_number"]; ?>

                </div>

                <div>

                    <h1>
                        <?php echo $player["first_name"]; ?>
                        <?php echo $player["last_name"]; ?>
                    </h1>

                    <p>
                        <?php echo $player["preferred_position"]; ?>
                    </p>

                </div>

            </div>

            <!-- EXTRA -->
            <div class="player-extra-info">

                <div class="player-info-item">

                    <span>Nacionalidad</span>

                    <strong>
                        <?php echo $player["nationality"]; ?>
                    </strong>

                </div>

                <div class="player-info-item">

                    <span>Edad</span>

                    <strong>
                        <?php echo $age; ?> años
                    </strong>

                </div>

            </div>

        </div>

    </section>

    <!-- STATS -->
    <section class="player-stats">

        <div class="section-title">

            <h2>
                Estadísticas
            </h2>

            <p>
                Rendimiento general del jugador
            </p>

        </div>

        <div class="player-stats-grid">

            <!-- GOALS -->
            <div class="player-stat-card">

                <span class="stat-icon">⚽</span>

                <strong>
                    <?php echo $stats["goals"] ?? 0; ?>
                </strong>

                <p>
                    Goles
                </p>

            </div>

            <!-- ASSISTS -->
            <div class="player-stat-card">

                <span class="stat-icon">🎯</span>

                <strong>
                    <?php echo $stats["assists"] ?? 0; ?>
                </strong>

                <p>
                    Asistencias
                </p>

            </div>

            <!-- YELLOW -->
            <div class="player-stat-card">

                <span class="stat-icon">🟨</span>

                <strong>
                    <?php echo $stats["yellow_cards"] ?? 0; ?>
                </strong>

                <p>
                    Amarillas
                </p>

            </div>

            <!-- RED -->
            <div class="player-stat-card">

                <span class="stat-icon">🟥</span>

                <strong>
                    <?php echo $stats["red_cards"] ?? 0; ?>
                </strong>

                <p>
                    Rojas
                </p>

            </div>

        </div>

    </section>

</main>

<?php require_once "../includes/footer.php"; ?>