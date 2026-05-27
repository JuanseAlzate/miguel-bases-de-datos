<?php

require_once '../config/database.php';

$db = new Database();
$conn = $db->connect();

$sql = <<<'SQL'
SELECT
    m.id,
    m.match_date,
    m.home_score,
    m.away_score,
    m.status,
    m.round_number,
    m.tournament,
    m.phase,
    home.name AS home_team,
    home.logo_url AS home_logo,
    away.name AS away_team,
    away.logo_url AS away_logo,
    s.name AS stadium,
    c.name AS city
FROM matches m
INNER JOIN teams home ON m.home_team_id = home.id
INNER JOIN teams away ON m.away_team_id = away.id
INNER JOIN stadiums s ON m.stadium_id = s.id
INNER JOIN cities c ON s.city_id = c.id
ORDER BY m.match_date DESC
SQL;

$stmt = $conn->prepare($sql);
$stmt->execute();

$matches = $stmt->fetchAll();

function formatDate(string $date): string
{
    return date('d/m/Y H:i', strtotime($date));
}

function getStatusLabel(string $status): string
{
    return match ($status) {
        'SCHEDULED' => 'Programado',
        'LIVE' => 'En vivo',
        'FINISHED' => 'Finalizado',
        default => $status,
    };
}

function getLogo(string $logo): string
{
    return '../' . ltrim($logo, '/');
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Partidos - Liga BetPlay</title>
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
                <a href="matches.php" class="active">Partidos</a>
                <a href="standings.php">Tabla</a>
                <a href="stats.php">Estadísticas</a>
                <a href="../auth/login.php" class="login-link">Iniciar sesión</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="section-title">
            <h2>Partidos</h2>
            <p>Calendario oficial Liga BetPlay 2026</p>
        </section>

        <?php if (empty($matches)): ?>
            <p>No hay partidos registrados.</p>
        <?php else: ?>
            <div class="matches-list">
                <?php foreach ($matches as $match): ?>
                    <a href="match_detail.php?id=<?php echo $match['id']; ?>" class="match-link">
                        <article class="match-card">
                            <div class="match-header">
                                <span>
                                    <?php echo htmlspecialchars($match['tournament']); ?> · Jornada <?php echo htmlspecialchars($match['round_number']); ?>
                                </span>
                                <span class="status status-<?php echo strtolower($match['status']); ?>">
                                    <?php echo htmlspecialchars(getStatusLabel($match['status'])); ?>
                                </span>
                            </div>

                            <div class="match-teams">
                                <div class="team">
                                    <img src="<?php echo getLogo($match['home_logo']); ?>" class="team-img" alt="Logo <?php echo htmlspecialchars($match['home_team']); ?>">
                                    <span><?php echo htmlspecialchars($match['home_team']); ?></span>
                                </div>

                                <div class="score">
                                    <?php if ($match['status'] === 'FINISHED' || $match['status'] === 'LIVE'): ?>
                                        <strong><?php echo $match['home_score']; ?> - <?php echo $match['away_score']; ?></strong>
                                    <?php else: ?>
                                        <strong><?php echo date('H:i', strtotime($match['match_date'])); ?></strong>
                                    <?php endif; ?>
                                </div>

                                <div class="team">
                                    <span><?php echo htmlspecialchars($match['away_team']); ?></span>
                                    <img src="<?php echo getLogo($match['away_logo']); ?>" class="team-img" alt="Logo <?php echo htmlspecialchars($match['away_team']); ?>">
                                </div>
                            </div>

                            <div class="match-footer">
                                <?php echo htmlspecialchars($match['stadium']); ?> · <?php echo htmlspecialchars($match['city']); ?><br>
                                <?php echo formatDate($match['match_date']); ?>
                            </div>
                        </article>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
