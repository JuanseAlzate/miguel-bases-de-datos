<?php

require_once "../config/database.php";

$db = new Database();
$conn = $db->connect();

/*
    CSS
*/
$pageStyles = [
    "matches_seasons.css"
];

/*
    Obtener temporadas
*/
$sql = "
    SELECT DISTINCT year
    FROM matches
    ORDER BY year DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute();

$years = $stmt->fetchAll();

/*
    Header
*/
require_once "../includes/header.php";

?>

<main class="container">

    <section class="section-title">

        <h2>
            Temporadas
        </h2>

        <p>
            Selecciona una temporada para explorar el torneo
        </p>

    </section>

    <div class="seasons-grid">

        <?php foreach ($years as $season): ?>

            <a
                href="matches_tournaments.php?year=<?php echo $season["year"]; ?>"
                class="season-card"
            >

                <div class="season-card-icon">
                    🏆
                </div>

                <h3>
                    <?php echo $season["year"]; ?>
                </h3>

                <p>
                    Ver temporada
                </p>

            </a>

        <?php endforeach; ?>

    </div>

</main>

<?php require_once "../includes/footer.php"; ?>