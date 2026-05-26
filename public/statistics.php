<?php

require_once "../config/database.php";

$db = new Database();
$conn = $db->connect();

/*
    CSS
*/
$pageStyles = [
    "statistics.css"
];

/*
    Parámetros
*/
$year = $_GET["year"] ?? null;
$tournament = $_GET["tournament"] ?? null;

if (!$year || !$tournament) {
    die("Torneo inválido");
}

/*
    Goleadores
*/
$sqlScorers = "
    SELECT 
        p.id,
        p.first_name,
        p.last_name,
        t.name AS team_name,
        t.logo_url,

        COUNT(e.id) AS goals

    FROM events e

    INNER JOIN players p
        ON e.player_id = p.id

    INNER JOIN teams t
        ON p.team_id = t.id

    INNER JOIN matches m
        ON e.match_id = m.id

    WHERE e.type = 'GOAL'
    AND m.year = :year
    AND m.tournament = :tournament

    GROUP BY p.id

    ORDER BY goals DESC

    LIMIT 10
";

/*
    Asistencias
*/
$sqlAssists = "
    SELECT 
        p.id,
        p.first_name,
        p.last_name,

        t.name AS team_name,
        t.logo_url,

        COUNT(e.id) AS assists

    FROM events e

    INNER JOIN players p
        ON e.player_id = p.id

    INNER JOIN teams t
        ON p.team_id = t.id

    INNER JOIN matches m
        ON e.match_id = m.id

    WHERE e.type = 'ASSIST'
    AND m.year = :year
    AND m.tournament = :tournament

    GROUP BY p.id

    ORDER BY assists DESC

    LIMIT 10
";

$stmtAssists = $conn->prepare($sqlAssists);

$stmtAssists->bindParam(":year", $year);
$stmtAssists->bindParam(":tournament", $tournament);

$stmtAssists->execute();

$assists = $stmtAssists->fetchAll();

/*
    Tarjetas amarillas
*/
$sqlYellowCards = "
    SELECT 
        p.id,
        p.first_name,
        p.last_name,

        t.name AS team_name,
        t.logo_url,

        COUNT(e.id) AS total

    FROM events e

    INNER JOIN players p
        ON e.player_id = p.id

    INNER JOIN teams t
        ON p.team_id = t.id

    INNER JOIN matches m
        ON e.match_id = m.id

    WHERE e.type = 'YELLOW_CARD'
    AND m.year = :year
    AND m.tournament = :tournament

    GROUP BY p.id

    ORDER BY total DESC

    LIMIT 10
";

$stmtYellow = $conn->prepare($sqlYellowCards);

$stmtYellow->bindParam(":year", $year);
$stmtYellow->bindParam(":tournament", $tournament);

$stmtYellow->execute();

$yellowCards = $stmtYellow->fetchAll();

/*
    Tarjetas rojas
*/
$sqlRedCards = "
    SELECT 
        p.id,
        p.first_name,
        p.last_name,

        t.name AS team_name,
        t.logo_url,

        COUNT(e.id) AS total

    FROM events e

    INNER JOIN players p
        ON e.player_id = p.id

    INNER JOIN teams t
        ON p.team_id = t.id

    INNER JOIN matches m
        ON e.match_id = m.id

    WHERE e.type = 'RED_CARD'
    AND m.year = :year
    AND m.tournament = :tournament

    GROUP BY p.id

    ORDER BY total DESC

    LIMIT 10
";

$stmtRed = $conn->prepare($sqlRedCards);

$stmtRed->bindParam(":year", $year);
$stmtRed->bindParam(":tournament", $tournament);

$stmtRed->execute();

$redCards = $stmtRed->fetchAll();

$stmtScorers = $conn->prepare($sqlScorers);

$stmtScorers->bindParam(":year", $year);
$stmtScorers->bindParam(":tournament", $tournament);

$stmtScorers->execute();

$scorers = $stmtScorers->fetchAll();

/*
    Header
*/
require_once "../includes/header.php";

/*
    Season nav
*/
require_once "../includes/season_nav.php";

?>

<main class="container">

    <section class="section-title">

        <h2>
            Estadísticas
        </h2>

        <p>
            <?php echo $tournament; ?> · <?php echo $year; ?>
        </p>

    </section>

    <!-- GOLEADORES -->
    <section class="stats-section">

        <div class="stats-header">

            <h3>
                ⚽ Goleadores
            </h3>

        </div>

        <div class="stats-table-wrapper">

            <table class="stats-table">

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Jugador</th>
                        <th>Equipo</th>
                        <th>Goles</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($scorers as $index => $player): ?>

                        <tr>

                            <td>
                                <?php echo $index + 1; ?>
                            </td>

                            <td>

                                <a
                                    href="player_detail.php?id=<?php echo $player["id"]; ?>"
                                    class="player-link"
                                >
                                    <?php echo $player["first_name"]; ?>
                                    <?php echo $player["last_name"]; ?>
                                </a>

                            </td>

                            <td class="stats-team">

                                <img
                                    src="../<?php echo $player["logo_url"]; ?>"
                                    class="stats-team-logo"
                                >

                                <span>
                                    <?php echo $player["team_name"]; ?>
                                </span>

                            </td>

                            <td>
                                <strong>
                                    <?php echo $player["goals"]; ?>
                                </strong>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </section>

    <!-- ASISTENCIAS -->
    <section class="stats-section">
                        
        <div class="stats-header">
                        
            <h3>
                🎯 Asistencias
            </h3>
                        
        </div>
                        
        <div class="stats-table-wrapper">
                        
            <table class="stats-table">
                        
                <thead>
                        
                    <tr>
                        
                        <th>#</th>
                        <th>Jugador</th>
                        <th>Equipo</th>
                        <th>Asistencias</th>
                        
                    </tr>
                        
                </thead>
                        
                <tbody>
                        
                    <?php foreach ($assists as $index => $player): ?>
                    
                        <tr>
                    
                            <td>
                                <?php echo $index + 1; ?>
                            </td>
                    
                            <td>
                                <a
                                    href="player_detail.php?id=<?php echo $player["id"]; ?>"
                                    class="player-link"
                                >
                                    <?php echo $player["first_name"]; ?>
                                    <?php echo $player["last_name"]; ?>
                                </a>
                            </td>
                    
                            <td class="stats-team">
                    
                                <img
                                    src="../<?php echo $player["logo_url"]; ?>"
                                    class="stats-team-logo"
                                >
                    
                                <span>
                                    <?php echo $player["team_name"]; ?>
                                </span>
                    
                            </td>
                    
                            <td>
                    
                                <strong>
                                    <?php echo $player["assists"]; ?>
                                </strong>
                    
                            </td>
                    
                        </tr>
                    
                    <?php endforeach; ?>
                    
                </tbody>
                    
            </table>
                    
        </div>
                    
    </section>

    <!-- AMARILLAS -->
    <section class="stats-section">

        <div class="stats-header">

            <h3>
                🟨 Tarjetas amarillas
            </h3>

        </div>

        <div class="stats-table-wrapper">

            <table class="stats-table">

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Jugador</th>
                        <th>Equipo</th>
                        <th>Amarillas</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($yellowCards as $index => $player): ?>

                        <tr>

                            <td>
                                <?php echo $index + 1; ?>
                            </td>

                            <td>

                                <a
                                    href="player_detail.php?id=<?php echo $player["id"]; ?>"
                                    class="player-link"
                                >
                                    <?php echo $player["first_name"]; ?>
                                    <?php echo $player["last_name"]; ?>
                                </a>

                            </td>

                            <td class="stats-team">

                                <img
                                    src="../<?php echo $player["logo_url"]; ?>"
                                    class="stats-team-logo"
                                >

                                <span>
                                    <?php echo $player["team_name"]; ?>
                                </span>

                            </td>

                            <td>
                                <strong>
                                    <?php echo $player["total"]; ?>
                                </strong>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </section>

    <!-- ROJAS -->
    <section class="stats-section">

        <div class="stats-header">

            <h3>
                🟥 Tarjetas rojas
            </h3>

        </div>

        <div class="stats-table-wrapper">

            <table class="stats-table">

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Jugador</th>
                        <th>Equipo</th>
                        <th>Rojas</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($redCards as $index => $player): ?>

                        <tr>

                            <td>
                                <?php echo $index + 1; ?>
                            </td>

                            <td>
                                <a
                                    href="player_detail.php?id=<?php echo $player["id"]; ?>"
                                    class="player-link"
                                >
                                    <?php echo $player["first_name"]; ?>
                                    <?php echo $player["last_name"]; ?>
                                </a>
                            </td>

                            <td class="stats-team">

                                <img
                                    src="../<?php echo $player["logo_url"]; ?>"
                                    class="stats-team-logo"
                                >

                                <span>
                                    <?php echo $player["team_name"]; ?>
                                </span>

                            </td>

                            <td>
                                <strong>
                                    <?php echo $player["total"]; ?>
                                </strong>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </section>

</main>

<?php require_once "../includes/footer.php"; ?>
