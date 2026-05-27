<?php

require_once "../config/database.php";

$db = new Database();
$conn = $db->connect();

/*
    CSS
*/
$pageStyles = [
    "admin_team_detail.css"
];

/*
    Team ID
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
require_once "../includes/admin_header.php";

?>

<!-- HEADER -->
<div class="team-detail-header">

    <div class="team-detail-info">

        ../<?php echo $team["logo_url"]; ?>"
            alt="<?php echo $team["name"]; ?>"
            class="team-detail-logo"
        >

        <div>

            <h2>
                <?php echo $team["name"]; ?>
            </h2>

            <p>
                Gestión de plantilla y jugadores
            </p>

        </div>

    </div>

    <div class="team-detail-actions">

        create_player.php?team_id=<?php echo $team["id"]; ?>"
            class="admin-btn"
        >
            + Agregar jugador
        </a>

    </div>

</div>

<!-- PLAYERS -->
<div class="admin-table-wrapper">

    <table class="admin-table">

        <thead>

            <tr>

                <th>#</th>
                <th>Jugador</th>
                <th>Posición</th>
                <th>Nacionalidad</th>
                <th>Acciones</th>

            </tr>

        </thead>

        <tbody>

            <?php foreach ($players as $player): ?>

                <tr>

                    <!-- NUMBER -->
                    <td>

                        <?php echo $player["shirt_number"]; ?>

                    </td>

                    <!-- NAME -->
                    <td>

                        <?php echo $player["first_name"]; ?>
                        <?php echo $player["last_name"]; ?>

                    </td>

                    <!-- POSITION -->
                    <td>

                        <?php echo $player["preferred_position"]; ?>

                    </td>

                    <!-- NATIONALITY -->
                    <td>

                        <?php echo $player["nationality"]; ?>

                    </td>

                    <!-- ACTIONS -->
                    <td>

                        <div class="admin-actions">

                            edit_player.php?id=<?php echo $player["id"]; ?>"
                                class="admin-action edit"
                            >
                                Editar
                            </a>

                            delete_player.php?id=<?php echo $player["id"]; ?>"
                                class="admin-action delete"
                                onclick="return confirm('¿Eliminar jugador?')"
                            >
                                Eliminar
                            </a>

                        </div>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>

<?php require_once "../includes/admin_footer.php"; ?>