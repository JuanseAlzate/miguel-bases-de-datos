<?php

require_once "../config/database.php";

$db = new Database();
$conn = $db->connect();

/*
    CSS
*/
$pageStyles = [
    "matches.css"
];

/*
    Parámetros
*/
$year = $_GET["year"] ?? null;
$tournament = $_GET["tournament"] ?? null;

if (!$year || !$tournament) {
    die("Temporada inválida");
}

/*
    Obtener partidos
*/
$sql = "
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

    WHERE m.year = :year
    AND m.tournament = :tournament

    ORDER BY m.match_date DESC
";

$stmt = $conn->prepare($sql);

$stmt->bindParam(":year", $year);
$stmt->bindParam(":tournament", $tournament);

$stmt->execute();

$matches = $stmt->fetchAll();

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

    <section class="section-title">

        <h2>
            Partidos
        </h2>

        <p>
            <?php echo $tournament; ?> · <?php echo $year; ?>
        </p>

    </section>

    <div class="matches-list">

        <?php foreach ($matches as $match): ?>

            <a
                href="match_detail.php?id=<?php echo $match['id']; ?>"
                class="match-card-link"
            >

                <article class="match-card">

                    <!-- HEADER -->
                    <div class="match-card-header">

                        <span>
                            Jornada <?php echo $match["round_number"]; ?>
                        </span>

                        <span class="match-status">
                            <?php echo $match["status"]; ?>
                        </span>

                    </div>

                    <!-- BODY -->
                    <div class="match-card-body">

                        <!-- LOCAL -->
                        <div class="match-team">

                            <img
                                src="../<?php echo $match["home_logo"]; ?>"
                                alt="<?php echo $match["home_team"]; ?>"
                                class="match-team-logo"
                            >

                            <span>
                                <?php echo $match["home_team"]; ?>
                            </span>

                        </div>

                        <!-- SCORE -->
                        <div class="match-score">

                            <strong>
                                <?php echo $match["home_score"]; ?>
                                -
                                <?php echo $match["away_score"]; ?>
                            </strong>

                        </div>

                        <!-- VISITANTE -->
                        <div class="match-team away-team">

                            <span>
                                <?php echo $match["away_team"]; ?>
                            </span>

                            <img
                                src="../<?php echo $match["away_logo"]; ?>"
                                alt="<?php echo $match["away_team"]; ?>"
                                class="match-team-logo"
                            >

                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="match-card-footer">

                        <span>
                            <?php echo $match["stadium_name"]; ?>
                        </span>

                        <span>
                            <?php echo date("d/m/Y H:i", strtotime($match["match_date"])); ?>
                        </span>

                    </div>

                </article>

            </a>

        <?php endforeach; ?>

    </div>

</main>

<?php require_once "../includes/footer.php"; ?>