<?php

session_start();

require_once "../config/database.php";

$db = new Database();
$conn = $db->connect();

/*
    Si ya inició sesión
*/
if (isset($_SESSION["user"])) {

    header("Location: ../admin/dashboard.php");
    exit;

}

$error = null;

/*
    Login
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $sql = "
        SELECT *
        FROM users
        WHERE email = :email
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":email", $email);

    $stmt->execute();

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {

        $_SESSION["user"] = [

            "id" => $user["id"],

            "name" => $user["name"],

            "lastname" => $user["lastname"],

            "email" => $user["email"],

            "role" => $user["role"]

        ];

        header("Location: ../admin/dashboard.php");
        exit;

    } else {

        $error = "Credenciales inválidas";

    }

}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión</title>

    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/login.css">

</head>
<body>

<main class="login-page">

    <div class="login-card">

        <h1>
            Liga BetPlay
        </h1>

        <p>
            Panel administrativo
        </p>

        <?php if ($error): ?>

            <div class="login-error">
                <?php echo $error; ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <!-- EMAIL -->
            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    required
                >

            </div>

            <!-- PASSWORD -->
            <div class="form-group">

                <label>Contraseña</label>

                <input
                    type="password"
                    name="password"
                    required
                >

            </div>

            <!-- BUTTON -->
            <button type="submit" class="login-btn">
                Ingresar
            </button>

        </form>

    </div>

</main>

</body>
</html>