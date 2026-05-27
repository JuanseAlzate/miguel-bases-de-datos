<?php

require_once "../config/database.php";

$db = new Database();
$conn = $db->connect();

/*
    CSS
*/
$pageStyles = [
    "teams.css"
];

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

/*
    Header
*/
require_once "../includes/header.php";

?>

<main class="container">

    <section class="section-title">

        <h2>
            Equipos
        </h2>

        <p>
            Clubes registrados en la Liga BetPlay
        </p>

    </section>

    <div class="teams-grid">

        <?php foreach ($teams as $team): ?>

            <a 
                href="team_detail.php?id=<?php echo $team["id"]; ?>"
                class="team-card"
            >

                <img 
                    src="../<?php echo $team["logo_url"]; ?>"
                    alt="<?php echo $team["name"]; ?>"
                    class="team-card-logo"
                >

                <h3>
                    <?php echo $team["name"]; ?>
                </h3>

                <p>
                    Ver plantilla
                </p>

            </a>

        <?php endforeach; ?>

    </div>

</main>

<?php require_once "../includes/footer.php"; ?>