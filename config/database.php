<?php

class Database
{
    private string $host = "localhost";
    private string $dbName = "ligabetplay"; // cambia si tu BD tiene otro nombre
    private string $username = "root";
    private string $password = "";
    private string $charset = "utf8mb4";

    public function connect(): PDO
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset={$this->charset}";

            $pdo = new PDO($dsn, $this->username, $this->password);

            // Manejo de errores
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Devuelve array asociativo
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Evita problemas con prepared statements
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            return $pdo;

        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
}
