<?php

require_once "../config/database.php";

$db = new Database();
$conn = $db->connect();

/*
    CSS
*/
$pageStyles = [
    "matches_tournaments.css"
];

/*
    Parámetros
*/
$year = $_GET["year"] ?? null;

if (!$year) {
    die("Temporada inválida");
}

/*
    Obtener torneos
*/
$sql = "
    SELECT DISTINCT tournament
    FROM matches
    WHERE year = :year
";

$stmt = $conn->prepare($sql);

$stmt->bindParam(":year", $year);

$stmt->execute();

$tournaments = $stmt->fetchAll();

/*
    Header
*/
require_once "../includes/header.php";

?>

<main class="container">

    <section class="section-title">

        <h2>
            Temporada <?php echo $year; ?>
        </h2>

        <p>
            Selecciona un torneo
        </p>

    </section>

    <div class="tournaments-grid">

        <?php foreach ($tournaments as $tournament): ?>

            <a
                href="matches.php?year=<?php echo $year; ?>&tournament=<?php echo $tournament["tournament"]; ?>"
                class="tournament-card"
            >

                <div class="tournament-icon">
                    ⚽
                </div>

                <h3>
                    <?php echo $tournament["tournament"]; ?>
                </h3>

                <p>
                    Ver información
                </p>

            </a>

        <?php endforeach; ?>

    </div>

</main>

<?php require_once "../includes/footer.php"; ?>