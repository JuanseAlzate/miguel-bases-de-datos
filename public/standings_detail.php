<?php

require_once "../config/database.php";

$db = new Database();
$conn = $db->connect();

/*
    CSS
*/
$pageStyles = [
    "standings.css"
];

/*
    Parámetros
*/
$year = $_GET["year"] ?? null;
$tournament = $_GET["tournament"] ?? null;

if (!$year || !$tournament) {
    die("Temporada o torneo inválido");
}

/*
    Variables navegación
*/

/*
    Obtener standings
*/
$sqlStandings = "
    SELECT *
    FROM standings
    WHERE year = :year
    AND tournament = :tournament
";

$stmtStandings = $conn->prepare($sqlStandings);

$stmtStandings->bindParam(":year", $year);
$stmtStandings->bindParam(":tournament", $tournament);

$stmtStandings->execute();

$standings = $stmtStandings->fetchAll();

/*
    Obtener tabla
*/
function getStandingsTable($conn, $standingId) {

    $sql = "
        SELECT 
            st.*,

            t.name,
            t.logo_url

        FROM standings_teams st

        INNER JOIN teams t
            ON st.team_id = t.id

        WHERE st.standing_id = :id

        ORDER BY st.position ASC
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":id", $standingId);

    $stmt->execute();

    return $stmt->fetchAll();

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

    <section class="section-title">

        <h2>
            Tabla de posiciones
        </h2>

        <p>
            Clasificación oficial de la temporada
        </p>

    </section>

    <?php foreach ($standings as $standing): ?>

        <?php
            $teams = getStandingsTable($conn, $standing["id"]);
        ?>

        <section class="standings-section">

            <div class="standings-header">

                <h3>
                    <?php echo $standing["tournament"]; ?>
                </h3>

            </div>

            <div class="table-wrapper">

                <table class="standings-table">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Equipo</th>
                            <th>PTS</th>
                            <th>PJ</th>
                            <th>G</th>
                            <th>E</th>
                            <th>P</th>
                            <th>GF</th>
                            <th>GC</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($teams as $team): ?>

                            <tr>

                                <td>
                                    <?php echo $team["position"]; ?>
                                </td>

                                <td class="team-column">

                                    ../<?php echo $team["logo_url"]; ?>"
                                        alt="<?php echo $team["name"]; ?>"
                                        class="team-table-logo"
                                    >

                                    <span>
                                        <?php echo $team["name"]; ?>
                                    </span>

                                </td>

                                <td>
                                    <strong>
                                        <?php echo $team["points"]; ?>
                                    </strong>
                                </td>

                                <td><?php echo $team["played"]; ?></td>
                                <td><?php echo $team["wins"]; ?></td>
                                <td><?php echo $team["draws"]; ?></td>
                                <td><?php echo $team["losses"]; ?></td>
                                <td><?php echo $team["goals_for"]; ?></td>
                                <td><?php echo $team["goals_against"]; ?></td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </section>

    <?php endforeach; ?>

</main>

<?php require_once "../includes/footer.php"; ?>