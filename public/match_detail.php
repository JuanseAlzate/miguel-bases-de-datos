<?php

require_once "../config/database.php";

$db = new Database();
$conn = $db->connect();

/*
    CSS
*/
$pageStyles = [
    "match_detail.css"
];

/*
    Match ID
*/
$matchId = $_GET["id"] ?? null;

if (!$matchId) {
    die("Partido no encontrado");
}

/*
    Partido
*/
$sqlMatch = "
    SELECT 
        m.*,

        home.name AS home_team,
        home.logo_url AS home_logo,

        away.name AS away_team,
        away.logo_url AS away_logo,

        s.name AS stadium_name

    FROM matches m

    INNER JOIN teams home
        ON m.home_team_id = home.id

    INNER JOIN teams away
        ON m.away_team_id = away.id

    INNER JOIN stadiums s
        ON m.stadium_id = s.id

    WHERE m.id = :id
";

$stmtMatch = $conn->prepare($sqlMatch);

$stmtMatch->bindParam(":id", $matchId);

$stmtMatch->execute();

$match = $stmtMatch->fetch();

if (!$match) {
    die("Partido no encontrado");
}

/*
    Variables navegación
*/
$year = $match["year"];
$tournament = $match["tournament"];

/*
    Eventos
*/
$sqlEvents = "
    SELECT 
        e.*,

        p.first_name,
        p.last_name,
        p.team_id

    FROM events e

    INNER JOIN players p
        ON e.player_id = p.id

    WHERE e.match_id = :id

    ORDER BY e.minute ASC
";

$stmtEvents = $conn->prepare($sqlEvents);

$stmtEvents->bindParam(":id", $matchId);

$stmtEvents->execute();

$events = $stmtEvents->fetchAll();

/*
    Alineaciones
*/
$sqlLineups = "
    SELECT 
        lp.*,

        p.first_name,
        p.last_name,
        p.team_id,
        p.shirt_number,

        l.team_id AS lineup_team_id,
        l.formation

    FROM lineup_players lp

    INNER JOIN players p
        ON lp.player_id = p.id

    INNER JOIN lineups l
        ON lp.lineup_id = l.id

    WHERE l.match_id = :id

    ORDER BY lp.is_starter DESC, p.last_name ASC
";

$stmtLineups = $conn->prepare($sqlLineups);

$stmtLineups->bindParam(":id", $matchId);

$stmtLineups->execute();

$lineups = $stmtLineups->fetchAll();

/*
    Separar equipos
*/
$homeLineup = [];
$awayLineup = [];

foreach ($lineups as $player) {

    if ($player["team_id"] == $match["home_team_id"]) {
        $homeLineup[] = $player;
    } else {
        $awayLineup[] = $player;
    }

}

/*
    Función iconos
*/
function getEventIcon($type) {

    return match($type) {

        "GOAL" => "⚽",
        "YELLOW_CARD" => "🟨",
        "RED_CARD" => "🟥",
        "SUBSTITUTION" => "🔄",

        default => "•"
    };

}

/*
    Header
*/
require_once "../includes/header.php";

/*
    Navegación temporada
*/
require_once "../includes/season_nav.php";

?>

<main class="container">

    <!-- MATCH HERO -->
    <section class="match-detail-hero">

        <div class="match-detail-card">

            <!-- TEAMS -->
            <div class="match-detail-teams">

                <!-- HOME -->
                <div class="detail-team">

                    <img
                        src="../<?php echo $match["home_logo"]; ?>"
                        alt="<?php echo $match["home_team"]; ?>"
                        class="detail-team-logo"
                    >

                    <h3>
                        <?php echo $match["home_team"]; ?>
                    </h3>

                </div>

                <!-- SCORE -->
                <div class="detail-score">

                    <strong>
                        <?php echo $match["home_score"]; ?>
                        -
                        <?php echo $match["away_score"]; ?>
                    </strong>

                    <span>
                        <?php echo $match["status"]; ?>
                    </span>

                </div>

                <!-- AWAY -->
                <div class="detail-team">

                    <img
                        src="../<?php echo $match["away_logo"]; ?>"
                        alt="<?php echo $match["away_team"]; ?>"
                        class="detail-team-logo"
                    >

                    <h3>
                        <?php echo $match["away_team"]; ?>
                    </h3>

                </div>

            </div>

            <!-- INFO -->
            <div class="match-detail-info">

                <span>
                    <?php echo $match["stadium_name"]; ?>
                </span>

                <span>
                    <?php echo date("d/m/Y H:i", strtotime($match["match_date"])); ?>
                </span>

            </div>

        </div>

    </section>

    <!-- TABS -->
    <div class="match-tabs">

        <a href="#events" class="match-tab active">
            Eventos
        </a>

        <a href="#lineups" class="match-tab">
            Alineaciones
        </a>

    </div>

    <!-- EVENTS -->
    <section class="events-section" id="events">

        <div class="section-title">

            <h2>
                Eventos
            </h2>

            <p>
                Resumen cronológico del partido
            </p>

        </div>

        <?php if (empty($events)): ?>

            <div class="empty-events">
                No hay eventos registrados.
            </div>

        <?php else: ?>

            <div class="timeline">

                <div class="timeline-line"></div>

                <?php foreach ($events as $event): ?>

                    <?php
                        $side =
                            $event["team_id"] == $match["home_team_id"]
                            ? "home"
                            : "away";
                    ?>

                    <div class="timeline-event <?php echo $side; ?>">

                        <div class="timeline-content">

                            <div class="event-minute">
                                <?php echo $event["minute"]; ?>'
                            </div>

                            <div class="event-card">

                                <div class="event-header">

                                    <span class="event-icon">
                                        <?php echo getEventIcon($event["type"]); ?>
                                    </span>

                                    <strong>
                                        <?php echo $event["first_name"]; ?>
                                        <?php echo $event["last_name"]; ?>
                                    </strong>

                                </div>

                                <p>
                                    <?php echo $event["description"]; ?>
                                </p>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>

    <!-- LINEUPS -->
    <section class="lineups-section" id="lineups">

        <div class="section-title">

            <h2>
                Alineaciones
            </h2>

            <p>
                Jugadores utilizados en el partido
            </p>

        </div>

        <div class="lineups-grid">

            <!-- LOCAL -->
            <div class="lineup-card">

                <div class="lineup-header">

                    <img
                        src="../<?php echo $match["home_logo"]; ?>"
                        class="lineup-team-logo"
                    >

                    <h3>
                        <?php echo $match["home_team"]; ?>
                    </h3>

                </div>

                <!-- TITULARES -->
                <div class="lineup-group">

                    <h4>Titulares</h4>

                    <?php foreach ($homeLineup as $player): ?>

                        <?php if ($player["is_starter"]): ?>

                            <div class="lineup-player">

                                <span>
                                    <?php echo $player["shirt_number"]; ?>
                                </span>

                                <p>
                                    <?php echo $player["first_name"]; ?>
                                    <?php echo $player["last_name"]; ?>
                                </p>

                            </div>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </div>

                <!-- SUPLENTES -->
                <div class="lineup-group">

                    <h4>Suplentes</h4>

                    <?php foreach ($homeLineup as $player): ?>

                        <?php if (!$player["is_starter"]): ?>

                            <div class="lineup-player">

                                <span>
                                    <?php echo $player["shirt_number"]; ?>
                                </span>

                                <p>
                                    <?php echo $player["first_name"]; ?>
                                    <?php echo $player["last_name"]; ?>
                                </p>

                            </div>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </div>

            </div>

            <!-- VISITANTE -->
            <div class="lineup-card">

                <div class="lineup-header">

                    <img
                        src="../<?php echo $match["away_logo"]; ?>"
                        class="lineup-team-logo"
                    >

                    <h3>
                        <?php echo $match["away_team"]; ?>
                    </h3>

                </div>

                <!-- TITULARES -->
                <div class="lineup-group">

                    <h4>Titulares</h4>

                    <?php foreach ($awayLineup as $player): ?>

                        <?php if ($player["is_starter"]): ?>

                            <div class="lineup-player">

                                <span>
                                    <?php echo $player["shirt_number"]; ?>
                                </span>

                                <p>
                                    <?php echo $player["first_name"]; ?>
                                    <?php echo $player["last_name"]; ?>
                                </p>

                            </div>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </div>

                <!-- SUPLENTES -->
                <div class="lineup-group">

                    <h4>Suplentes</h4>

                    <?php foreach ($awayLineup as $player): ?>

                        <?php if (!$player["is_starter"]): ?>

                            <div class="lineup-player">

                                <span>
                                    <?php echo $player["shirt_number"]; ?>
                                </span>

                                <p>
                                    <?php echo $player["first_name"]; ?>
                                    <?php echo $player["last_name"]; ?>
                                </p>

                            </div>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </div>

            </div>

        </div>

    </section>

</main>

<?php require_once "../includes/footer.php"; ?>