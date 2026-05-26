<?php

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<aside class="admin-sidebar">

    <!-- BRAND -->
    <div class="admin-brand">

        <h2>
            Liga BetPlay
        </h2>

        <p>
            Panel administrativo
        </p>

    </div>

    <!-- NAVIGATION -->
    <nav class="admin-nav">

        <!-- DASHBOARD -->
        <a
            href="../admin/dashboard.php"
            class="<?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>"
        >
            📊 Dashboard
        </a>

        <!-- TEAMS -->
        <a
            href="../admin/teams.php"
            class="<?php echo $currentPage === 'teams.php' ? 'active' : ''; ?>"
        >
            🛡️ Equipos
        </a>

        <!-- MATCHES -->
        <a
            href="../admin/matches.php"
            class="<?php echo $currentPage === 'matches.php' ? 'active' : ''; ?>"
        >
            ⚽ Partidos
        </a>

        <!-- USERS -->
        <?php if ($_SESSION["user"]["role"] === "ADMIN"): ?>

            <a
                href="../admin/users.php"
                class="<?php echo $currentPage === 'users.php' ? 'active' : ''; ?>"
            >
                🔐 Usuarios
            </a>

        <?php endif; ?>

        <!-- LOGOUT -->
        <a
            href="../auth/logout.php"
        >
            🚪 Cerrar sesión
        </a>

    </nav>

</aside>