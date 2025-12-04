<?php
require_once __DIR__ . '/../config/database.php';

class Usuario {

    public static function findByUsername($username) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = :u");
        $stmt->execute([':u' => $username]);
        return $stmt->fetch();
    }
}
