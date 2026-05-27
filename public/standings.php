<?php

require_once "../config/database.php";

$db = new Database();
$conn = $db->connect();

/*
    Obtener temporadas únicas
*/
$sql = "SELECT DISTINCT year FROM matches ORDER BY year DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$years = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Temporadas - Liga BetPlay</title>

    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

    <header class="site-header">
        <div class="container header-content">
            <div class="brand">
                <div class="brand-logo">⚽</div>
                <div>
                    <h1>Liga BetPlay</h1>
                    <p>Sistema de gestión 2026</p>
                </div>
            </div>

            <nav class="main-nav">
                <a href="index.php">Inicio</a>
                <a href="matches.php">Partidos</a>
                <a href="standings.php" class="active">Tablas</a>
                <a href="stats.php">Estadísticas</a>
                <a href="../auth/login.php" class="login-link">Iniciar sesión</a>
            </nav>
        </div>
    </header>

<main class="container">

    <section class="section-title">
        <h2>Temporadas</h2>
        <p>Selecciona una temporada para ver las tablas de posiciones</p>
    </section>

    <?php if (empty($years)): ?>
        <p>No hay datos disponibles.</p>
    <?php else: ?>

        <div class="seasons-grid">

            <?php foreach ($years as $year): ?>

                <a href="standings_detail.php?year=<?php echo $year["year"]; ?>" class="season-card">

                    <div class="season-icon">📅</div>

                    <h3><?php echo $year["year"]; ?></h3>

                    <p>Ver tablas</p>

                </a>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</main>

</body>
</html>