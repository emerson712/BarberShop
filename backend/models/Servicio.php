<?php
// backend/models/Servicio.php

require_once __DIR__ . '/../config/database.php';

class Servicio {

    /**
     * Obtiene todos los servicios.
     */
    public static function all() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM servicios ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }

    /**
     * Busca un servicio por ID.
     */
    public static function find($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Crea un nuevo servicio.
     */
    public static function create($data) {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO servicios (nombre, precio, duracion_minutos)
            VALUES (:n, :p, :d)
        ");
        return $stmt->execute([
            ':n' => $data['nombre'],
            ':p' => $data['precio'] ?? 0.0,
            ':d' => $data['duracion_minutos'] ?? 30,
        ]);
    }

    /**
     * Actualiza un servicio existente.
     */
    public static function update($data) {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE servicios
            SET nombre = :n, precio = :p, duracion_minutos = :d
            WHERE id = :id
        ");
        return $stmt->execute([
            ':n'  => $data['nombre'],
            ':p'  => $data['precio'] ?? 0.0,
            ':d'  => $data['duracion_minutos'] ?? 30,
            ':id' => $data['id'],
        ]);
    }

    /**
     * Elimina un servicio por ID.
     */
    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM servicios WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}