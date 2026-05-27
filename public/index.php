<?php

require_once "../config/database.php";

$db = new Database();
$conn = $db->connect();

/*
    CSS de esta página
*/
$pageStyles = [
    "index.css"
];

/*
    Última temporada disponible
*/
$sqlYear = "SELECT MAX(year) AS year FROM matches";

$stmtYear = $conn->prepare($sqlYear);
$stmtYear->execute();

$yearData = $stmtYear->fetch();
$lastYear = $yearData["year"] ?? null;

/*
    Último torneo jugado
*/
$sqlTournament = "
    SELECT tournament
    FROM matches
    WHERE year = :year
    AND status = 'FINISHED'
    ORDER BY match_date DESC
    LIMIT 1
";

$stmtTournament = $conn->prepare($sqlTournament);
$stmtTournament->bindParam(":year", $lastYear);
$stmtTournament->execute();

$tournamentData = $stmtTournament->fetch();
$lastTournament = $tournamentData["tournament"] ?? null;

/*
    Último partido finalizado
*/
$sql = "
    SELECT 
        m.*,

        home.name AS home_team,
        home.logo_url AS home_logo,

        away.name AS away_team,
        away.logo_url AS away_logo

    FROM matches m
    INNER JOIN teams home ON m.home_team_id = home.id
    INNER JOIN teams away ON m.away_team_id = away.id

    WHERE m.status = 'FINISHED'

    ORDER BY m.year DESC, m.match_date DESC
    LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->execute();

$lastMatch = $stmt->fetch();

/*
    HEADER
*/
require_once "../includes/header.php";

?>

<main>

    <!-- HERO -->
    <section class="hero">

        <div class="container hero-grid">

            <!-- TEXTO -->
            <div class="hero-text">

                <span class="badge">
                    Liga BetPlay <?php echo $lastYear; ?>
                </span>

                <h2>
                    Consulta partidos, resultados y clasificación en tiempo real
                </h2>

                <p>
                    Plataforma pública para consultar el calendario de partidos,
                    resultados, tabla de posiciones y estadísticas del fútbol profesional colombiano.
                </p>

                <div class="hero-actions">

                    <a
                        href="matches.php?year=<?php echo $lastYear; ?>&tournament=<?php echo $lastTournament; ?>"
                        class="btn btn-primary"
                    >
                        Ver partidos
                    </a>

                    <a
                        href="standings_detail.php?year=<?php echo $lastYear; ?>&tournament=<?php echo $lastTournament; ?>"
                        class="btn btn-secondary"
                    >
                        Ver tabla
                    </a>

                </div>

            </div>

            <!-- CARD PARTIDO -->
            <?php if ($lastMatch): ?>

                <a
                    href="match_detail.php?id=<?php echo $lastMatch['id']; ?>"
                    class="hero-card-link"
                >

                    <div class="hero-card">

                        <div class="match-preview-header">
                            <span>Último partido</span>
                            <strong>Finalizado</strong>
                        </div>

                        <div class="match-preview-body">

                            <!-- LOCAL -->
                            <div class="team-preview">

                                <img
                                    src="../<?php echo $lastMatch['home_logo']; ?>"
                                    alt="<?php echo $lastMatch['home_team']; ?>"
                                    class="team-logo"
                                >

                                <p>
                                    <?php echo $lastMatch["home_team"]; ?>
                                </p>

                            </div>

                            <!-- SCORE -->
                            <div class="match-time">

                                <strong>
                                    <?php echo $lastMatch["home_score"]; ?>
                                    -
                                    <?php echo $lastMatch["away_score"]; ?>
                                </strong>

                                <span>Final</span>

                            </div>

                            <!-- VISITANTE -->
                            <div class="team-preview">

                                <img
                                    src="../<?php echo $lastMatch['away_logo']; ?>"
                                    alt="<?php echo $lastMatch['away_team']; ?>"
                                    class="team-logo"
                                >

                                <p>
                                    <?php echo $lastMatch["away_team"]; ?>
                                </p>

                            </div>

                        </div>

                        <div class="match-preview-footer">

                            <?php echo date("d/m/Y H:i", strtotime($lastMatch["match_date"])); ?>

                        </div>

                    </div>

                </a>

            <?php endif; ?>

        </div>

    </section>

    <!-- CARDS -->
    <section class="quick-access">

        <div class="container">

            <div class="section-title">

                <h2>
                    Explora el sistema
                </h2>

                <p>
                    Consulta la información principal del torneo
                </p>

            </div>

            <div class="cards-grid">

                <!-- PARTIDOS -->
                <div class="info-card">

                    <div class="card-icon">⚽</div>

                    <h3>Partidos</h3>

                    <p>
                        Consulta calendario, resultados y partidos disponibles
                    </p>

                    <a
                        href="matches.php?year=<?php echo $lastYear; ?>&tournament=<?php echo $lastTournament; ?>"
                    >
                        Ver partidos →
                    </a>

                </div>

                <!-- TABLA -->
                <div class="info-card">

                    <div class="card-icon">🏆</div>

                    <h3>Tabla de posiciones</h3>

                    <p>
                        Consulta clasificación, puntos y rendimiento
                    </p>

                    <a
                        href="standings_detail.php?year=<?php echo $lastYear; ?>&tournament=<?php echo $lastTournament; ?>"
                    >
                        Ver tabla →
                    </a>

                </div>

                <!-- STATS -->
                <div class="info-card">

                    <div class="card-icon">📊</div>

                    <h3>Estadísticas</h3>

                    <p>
                        Consulta datos de jugadores y rendimiento
                    </p>

                    <a href="statistics.php?year=<?php echo $lastYear; ?>&tournament=<?php echo $lastTournament; ?>">
                         Ver estadísticas →
                    </a>

                </div>

            </div>

        </div>

    </section>

    <!-- ADMIN -->
    <section class="admin-callout">

        <div class="container admin-box">

            <div>

                <h2>
                    Panel administrativo
                </h2>

                <p>
                    Gestiona equipos, partidos, eventos y datos del sistema.
                </p>

            </div>

            <a href="../auth/login.php">
                Acceder
            </a>

        </div>

    </section>

</main>

<?php require_once "../includes/footer.php"; ?>