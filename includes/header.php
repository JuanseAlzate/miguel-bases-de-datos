<?php

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Liga BetPlay</title>

    <!-- GLOBAL -->
    <link rel="stylesheet" href="../assets/css/styles.css">

    <!-- CSS DINÁMICO -->
    <?php if (isset($pageStyles)): ?>

        <?php foreach ($pageStyles as $style): ?>

            <link 
                rel="stylesheet" 
                href="../assets/css/<?php echo $style; ?>"
            >

        <?php endforeach; ?>

    <?php endif; ?>

</head>
<body>

<header class="site-header">

    <div class="container header-content">

        <!-- BRAND -->
        <div class="brand">

            <div class="brand-logo">⚽</div>

            <div>
                <h1>Liga BetPlay</h1>
                <p>Sistema de gestión 2026</p>
            </div>

        </div>

        <!-- NAV -->
        <nav class="main-nav">

            <a 
                href="index.php"
                class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>"
            >
                Inicio
            </a>

            <a 
                href="matches_seasons.php"
                class="<?php echo str_contains($currentPage, 'match') || str_contains($currentPage, 'standings') || str_contains($currentPage, 'statistics') ? 'active' : ''; ?>"
            >
                Temporadas
            </a>

            <a 
                href="teams.php" 
                class="<?php echo $currentPage === 'teams.php' ? 'active' : ''; ?>"
            >
                Equipos
            </a>

            <a 
                href="../auth/login.php"
                class="login-link"
            >
                Iniciar sesión
            </a>

        </nav>

    </div>

</header>