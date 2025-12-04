<?php
require_once __DIR__ . '/../config/database.php';

class Cliente {

    public static function all() {
        global $pdo;
        // La consulta ahora solo trae las columnas existentes (id, nombre, email, telefono, notas)
        $stmt = $pdo->query("SELECT * FROM clientes ORDER BY id DESC"); 
        return $stmt->fetchAll();
    }

    public static function find($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public static function create($data) {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO clientes (nombre, email, telefono, notas)
            VALUES (:n, :e, :t, :notes)
        ");
        return $stmt->execute([
            ':n' => $data['nombre'],
            ':e' => $data['email'] ?? null,
            ':t' => $data['telefono'] ?? null,
            ':notes' => $data['notas'] ?? null, // Campo Notas añadido
        ]);
    }

    public static function update($data) {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE clientes
            SET nombre = :n, email = :e, telefono = :t, notas = :notes
            WHERE id = :id
        ");
        return $stmt->execute([
            ':n'  => $data['nombre'],
            ':e'  => $data['email'] ?? null,
            ':t'  => $data['telefono'] ?? null,
            ':notes' => $data['notas'] ?? null, // Campo Notas añadido
            ':id' => $data['id'],
        ]);
    }

    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}