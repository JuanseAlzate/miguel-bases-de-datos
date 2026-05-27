<?php

require_once "../config/database.php";

$db = new Database();
$conn = $db->connect();

$year = $_GET["year"] ?? null;

if (!$year) {
    die("Temporada no válida");
}

/*
    Obtener standings del año
*/
$sql = "
    SELECT s.id, s.tournament
    FROM standings s
    WHERE s.year = :year
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(":year", $year);
$stmt->execute();

$standings = $stmt->fetchAll();

/*
    Obtener equipos por standing
*/
function getTable($conn, $standingId) {
    $sql = "
        SELECT 
            st.*,
            t.name,
            t.logo_url
        FROM standings_teams st
        INNER JOIN teams t ON st.team_id = t.id
        WHERE st.standing_id = :id
        ORDER BY st.position ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $standingId);
    $stmt->execute();

    return $stmt->fetchAll();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tabla <?php echo $year; ?></title>

    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<header class="site-header">
    <div class="container header-content">

        <div class="brand">
            <div class="brand-logo">🏆</div>
            <div>
                <h1>Liga BetPlay</h1>
                <p>Temporada <?php echo $year; ?></p>
            </div>
        </div>

        <nav class="main-nav">
            <a href="standings.php">← Volver</a>
        </nav>

    </div>
</header>

<main class="container">

    <section class="section-title">
        <h2>Tablas de posición</h2>
    </section>

    <?php foreach ($standings as $standing): ?>

        <?php $teams = getTable($conn, $standing["id"]); ?>

        <section class="standings-table">

            <h2><?php echo $standing["tournament"]; ?></h2>

            <div class="table-container">

                <table>

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Equipo</th>
                            <th>Pts</th>
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

                                <td><?php echo $team["position"]; ?></td>

                                <td class="team-cell">
                                    <?php echo "../" . $team["logo_url"]; ?> class="team-img">
                                    <?php echo $team["name"]; ?>
                                </td>

                                <td><strong><?php echo $team["points"]; ?></strong></td>
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

</body>
</html>