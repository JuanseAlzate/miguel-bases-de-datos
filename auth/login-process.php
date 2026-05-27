<?php

require_once "../config/database.php";
require_once "../config/session.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: login.php");
    exit;
}

$email = trim($_POST['email']);
$password = trim($_POST['password']);

$sql = "SELECT * FROM users WHERE email = :email";

$stmt = $conn->prepare($sql);

$stmt->bindParam(':email', $email);

$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {

    $_SESSION['error'] = "El usuario no existe";

    header("Location: login.php");
    exit;
}

if (!password_verify($password, $user['password'])) {

    $_SESSION['error'] = "Contraseña incorrecta";

    header("Location: login.php");
    exit;
}

$_SESSION['user'] = [

    'id' => $user['id'],
    'name' => $user['name'],
    'lastname' => $user['lastname'],
    'email' => $user['email'],
    'role' => $user['role']

];

if ($user['role'] === 'ADMIN') {

    header("Location: ../admin/dashboard.php");
    exit;
}

if ($user['role'] === 'EDITOR') {

    header("Location: ../admin/dashboard.php");
    exit;
}

header("Location: ../public/index.php");