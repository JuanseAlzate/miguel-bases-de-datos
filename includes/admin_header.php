<?php

require_once "../middleware/auth.php";

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Panel administrativo</title>

    <!-- GLOBAL -->
     <link rel="stylesheet" href="../assets/css/styles.css">

    <!-- ADMIN -->
    <link rel="stylesheet" href="../assets/css/admin.css">

    <!-- CSS EXTRA -->
    <?php if (isset($pageStyles)): ?>

        <?php foreach ($pageStyles as $style): ?>

            <link rel="stylesheet" href="../assets/css/<?php echo $style; ?>">

        <?php endforeach; ?>

    <?php endif; ?>

</head>
<body>

<div class="admin-layout">

    <?php require_once "admin_sidebar.php"; ?>

    <main class="admin-main">

        <!-- TOPBAR -->
        <header class="admin-topbar">

            <div>

                <h2>
                    Panel administrativo
                </h2>

            </div>

            <div class="admin-user">

                <?php echo $_SESSION["user"]["name"]; ?>
                <?php echo $_SESSION["user"]["lastname"]; ?>

                ·

                <?php echo $_SESSION["user"]["role"]; ?>

            </div>

        </header>

        <!-- CONTENT -->
        <div class="admin-content">