<?php

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<section class="season-navigation">

    <div class="container">

        <div class="season-nav-header">

            <h2>
                Temporada <?php echo $year; ?>
            </h2>

            <span>
                <?php echo $tournament ?? "Liga BetPlay"; ?>
            </span>

        </div>

        <nav class="season-nav">

            <a
                href="matches.php?year=<?php echo $year; ?>&tournament=<?php echo $tournament; ?>"
                class="<?php echo $currentPage === 'matches.php' ? 'active' : ''; ?>"
            >
                Partidos
            </a>

            <a 
                href="standings_detail.php?year=<?php echo $year; ?>&tournament=<?php echo $tournament; ?>"
                class="<?php echo $currentPage === 'standings_detail.php' ? 'active' : ''; ?>"
            >
                Tabla
            </a>

            <a
                href="statistics.php?year=<?php echo $year; ?>&tournament=<?php echo $tournament; ?>"
                class="<?php echo $currentPage === 'statistics.php' ? 'active' : ''; ?>"
            >
                Estadísticas
            </a>

        </nav>

    </div>

</section>
