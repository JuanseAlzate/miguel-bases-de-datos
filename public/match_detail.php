<?php

require_once "../config/database.php";

$db = new Database();
$conn = $db->connect();

/*
    ID del partido
*/
$matchId = $_GET["id"] ?? null;

if (!$matchId) {
    die("Partido no encontrado");
}

/*
    PARTIDO
*/
$sqlMatch = "
    SELECT 
        m.*,

        home.name AS home_team,
        home.logo_url AS home_logo,

        away.name AS away_team,
        away.logo_url AS away_logo,

        s.name AS stadium,
        c.name AS city

    FROM matches m
    INNER JOIN teams home ON m.home_team_id = home.id
    INNER JOIN teams away ON m.away_team_id = away.id
    INNER JOIN stadiums s ON m.stadium_id = s.id
    INNER JOIN cities c ON s.city_id = c.id

    WHERE m.id = :id
";

$stmt = $conn->prepare($sqlMatch);
$stmt->bindParam(":id", $matchId);
$stmt->execute();

$match = $stmt->fetch();

if (!$match) {
    die("Partido no encontrado");
}

/*
    EVENTOS
*/
$sqlEvents = "
    SELECT 
        e.*,
        p.first_name,
        p.last_name,
        p.team_id

    FROM events e
    INNER JOIN players p ON e.player_id = p.id
    WHERE e.match_id = :id
    ORDER BY e.minute ASC
";

$stmtEvents = $conn->prepare($sqlEvents);
$stmtEvents->bindParam(":id", $matchId);
$stmtEvents->execute();

$events = $stmtEvents->fetchAll();

/* FUNCIONES */

function getLogo($logo) {
    return "../" . $logo;
}

function getEventIcon($type) {
    return match($type) {
        "GOAL" => "⚽",
        "YELLOW_CARD" => "🟨",
        "RED_CARD" => "🟥",
        "SUBSTITUTION" => "🔄",
        default => "•"
    };
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del partido</title>

    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<header class="site-header">
    <div class="container header-content">

        <div class="brand">
            <div class="brand-logo">⚽</div>
            <div>
                <h1>Liga BetPlay</h1>
                <p>Detalle del partido</p>
            </div>
        </div>

        <nav class="main-nav">
            <a href="matches.php">← Volver</a>
        </nav>

    </div>
</header>

<main class="container">

    <!-- HEADER PARTIDO -->
    <section class="match-detail">

        <div class="teams">

            <!-- LOCAL -->
            <div class="team">
                <img src="<?php echo getLogo($match['home_logo']); ?>" class="team-img">
                <span><?php echo $match["home_team"]; ?></span>
            </div>

            <!-- SCORE -->
            <div class="score">
                <strong>
                    <?php echo $match["home_score"]; ?> - <?php echo $match["away_score"]; ?>
                </strong>
            </div>

            <!-- VISITANTE -->
            <div class="team">
                <span><?php echo $match["away_team"]; ?></span>
                <img src="<?php echo getLogo($match['away_logo']); ?>" class="team-img">
            </div>

        </div>

        <div class="match-info">
            <?php echo $match["stadium"]; ?> · <?php echo $match["city"]; ?><br>
            <?php echo date("d/m/Y H:i", strtotime($match["match_date"])); ?>
        </div>

    </section>

    <!-- EVENTOS -->
    <section class="events">

        <h2>Eventos del partido</h2>

        <?php if (empty($events)): ?>
            <p>No hubo eventos registrados.</p>
        <?php else: ?>

            <div class="timeline">

                <div class="timeline-line"></div>

                <div class="events-list">

                    <?php foreach ($events as $event): ?>

<div class="event-item event-<?php echo $event["team_id"] == $match["home_team_id"] ? 'home' : 'away'; ?>">


                            <div class="event-minute">
                                <?php echo $event["minute"]; ?>'
                            </div>

                            <div class="event-content">

                                <div class="event-row">
                                    <span class="event-icon">
                                        <?php echo getEventIcon($event["type"]); ?>
                                    </span>

                                    <span class="event-player">
                                        <?php echo $event["first_name"] . " " . $event["last_name"]; ?>
                                    </span>
                                </div>

                                <div class="event-description">
                                    <?php echo $event["description"]; ?>
                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        <?php endif; ?>

    </section>

</main>

</body>
</html>