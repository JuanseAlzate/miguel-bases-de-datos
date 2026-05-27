<?php

$pageStyles = [];

require_once "../includes/admin_header.php";

?>

<div class="section-title">

    <h2>
        Dashboard
    </h2>

    <p>
        Resumen general del sistema
    </p>

</div>

<div class="dashboard-grid">

    <!-- EQUIPOS -->
    <div class="dashboard-card">

        <span>🛡️</span>

        <h3>
            Equipos
        </h3>

        <p>
            Gestiona equipos registrados
        </p>

    </div>

    <!-- PLAYERS -->
    <div class="dashboard-card">

        <span>👤</span>

        <h3>
            Jugadores
        </h3>

        <p>
            Administra plantillas y datos
        </p>

    </div>

    <!-- MATCHES -->
    <div class="dashboard-card">

        <span>⚽</span>

        <h3>
            Partidos
        </h3>

        <p>
            Gestiona partidos y eventos
        </p>

    </div>

    <!-- USERS -->
    <?php if ($_SESSION["user"]["role"] === "ADMIN"): ?>

        <div class="dashboard-card">

            <span>🔐</span>

            <h3>
                Usuarios
            </h3>

            <p>
                Administra usuarios del sistema
            </p>

        </div>

    <?php endif; ?>

</div>

<?php require_once "../includes/admin_footer.php"; ?>