<?php

require_once "auth.php";

$role = $_SESSION['user']['role'];

if ($role !== 'ADMIN' && $role !== 'EDITOR') {

    die("No autorizado");
}