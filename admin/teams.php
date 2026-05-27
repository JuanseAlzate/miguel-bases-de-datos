<?php

require_once "../config/database.php";

$db = new Database();
$conn = $db->connect();

/*
    CSS
*/
$pageStyles = [
    "admin_teams.css"
];

/*
    Header
*/
require_once "../includes/admin_header.php";

/*
    Equipos
*/
$sql = "
    SELECT *
    FROM teams
    ORDER BY name ASC
";

$stmt = $conn->prepare($sql);

$stmt->execute();

$teams = $stmt->fetchAll();

?>

<div class="admin-page-header">

    <div>

        <h2>
            Equipos
        </h2>

        <p>
            Gestiona los equipos registrados
        </p>

    </div>

    <a
        href="create_team.php"
        class="admin-btn"
    >
        + Crear equipo
    </a>

</div>

<div class="admin-table-wrapper">

    <table class="admin-table">

        <thead>

            <tr>

                <th>Logo</th>
                <th>Nombre</th>
                <th>Acciones</th>

            </tr>

        </thead>

        <tbody>

            <?php foreach ($teams as $team): ?>

                <tr>

                    <!-- LOGO -->
                    <td>

                        <img
                            src="../<?php echo $team["logo_url"]; ?>"
                            alt="<?php echo $team["name"]; ?>"
                            class="admin-team-logo"
                        >

                    </td>

                    <!-- NAME -->
                    <td>

                        <a
                            href="team_detail.php?id=<?php echo $team["id"]; ?>"
                            class="admin-team-link"
                        >
                            <?php echo $team["name"]; ?>
                        </a>

                    </td>

                    <!-- ACTIONS -->
                    <td>

                        <div class="admin-actions">

                            <a
                                href="team_detail.php?id=<?php echo $team["id"]; ?>"
                                class="admin-action view"
                            >
                                Ver equipo
                            </a>

                            <a
                                href="edit_team.php?id=<?php echo $team["id"]; ?>"
                                class="admin-action edit"
                            >
                                Editar
                            </a>

                            <a
                                href="delete_team.php?id=<?php echo $team["id"]; ?>"
                                class="admin-action delete"
                                onclick="return confirm('¿Eliminar equipo?')"
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