<?php

require_once "../config/database.php";

$db = new Database();
$conn = $db->connect();

/*
    CSS
*/
$pageStyles = [
    "team_detail.css"
];

/*
    Parámetro
*/
$teamId = $_GET["id"] ?? null;

if (!$teamId) {
    die("Equipo no encontrado");
}

/*
    Equipo
*/
$sqlTeam = "
    SELECT *
    FROM teams
    WHERE id = :id
";

$stmtTeam = $conn->prepare($sqlTeam);

$stmtTeam->bindParam(":id", $teamId);

$stmtTeam->execute();

$team = $stmtTeam->fetch();

if (!$team) {
    die("Equipo no encontrado");
}

/*
    Jugadores
*/
$sqlPlayers = "
    SELECT *
    FROM players
    WHERE team_id = :id
    ORDER BY shirt_number ASC
";

$stmtPlayers = $conn->prepare($sqlPlayers);

$stmtPlayers->bindParam(":id", $teamId);

$stmtPlayers->execute();

$players = $stmtPlayers->fetchAll();

/*
    Header
*/
require_once "../includes/header.php";

?>

<main class="container">

    <!-- HERO -->
    <section class="team-detail-hero">

        <img
            src="../<?php echo $team["logo_url"]; ?>"
            alt="<?php echo $team["name"]; ?>"
            class="team-detail-logo"
        >

        <h2>
            <?php echo $team["name"]; ?>
        </h2>

    </section>

    <!-- PLAYERS -->
    <section>

        <div class="section-title">

            <h2>
                Plantilla
            </h2>

            <p>
                Jugadores registrados
            </p>

        </div>

        <div class="players-grid">

            <?php foreach ($players as $player): ?>
                <a 
                    href="player_detail.php?id=<?php echo $player["id"]; ?>"
                    class="player-card-link"
                >
                    <div class="player-card">

                        <div class="player-number">
                            <?php echo $player["shirt_number"]; ?>
                        </div>
                
                        <h3>
                            <?php echo $player["first_name"]; ?>
                            <?php echo $player["last_name"]; ?>
                        </h3>
                
                        <p>
                            <?php echo $player["preferred_position"]; ?>
                        </p>

                    </div>
                </a>

            <?php endforeach; ?>

        </div>

    </section>

</main>

<?php require_once "../includes/footer.php"; ?>