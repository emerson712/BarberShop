<?php
// Configuración de conexión a PostgreSQL
$host = 'localhost';
$port = '5432';
$db   = 'BarberShop';
$user = 'postgres';      // Cambiar por el usuario real de PostgreSQL
$pass = 'Tumaco2025';  // Cambiar por la contraseña real

$dsn = "pgsql:host=$host;port=$port;dbname=$db";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
