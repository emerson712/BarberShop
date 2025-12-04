<?php
require_once __DIR__ . '/../config/database.php';

class Cita {

    /**
     * Obtiene todas las citas, incluyendo el nombre del cliente.
     */
    public static function all() {
        global $pdo;
        // Se une con la tabla clientes para mostrar el nombre en el frontend
        $stmt = $pdo->query("
            SELECT 
                citas.id, 
                citas.fecha_hora, 
                citas.servicio, 
                citas.estado,
                clientes.nombre AS cliente_nombre,
                clientes.id AS cliente_id
            FROM citas
            JOIN clientes ON citas.cliente_id = clientes.id
            ORDER BY citas.fecha_hora DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Busca una cita por ID.
     */
    public static function find($id) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT 
                citas.*,
                clientes.nombre AS cliente_nombre
            FROM citas
            JOIN clientes ON citas.cliente_id = clientes.id
            WHERE citas.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Crea una nueva cita (Agendar).
     */
    public static function create($data) {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO citas (cliente_id, fecha_hora, servicio, estado)
            VALUES (:ci, :fh, :s, 'Agendada')
        ");
        return $stmt->execute([
            ':ci' => $data['cliente_id'],
            ':fh' => $data['fecha_hora'],
            ':s'  => $data['servicio'],
        ]);
    }

    /**
     * Actualiza una cita existente (Reprogramar/Editar).
     */
    public static function update($data) {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE citas
            SET cliente_id = :ci, fecha_hora = :fh, servicio = :s
            WHERE id = :id
        ");
        return $stmt->execute([
            ':ci' => $data['cliente_id'],
            ':fh' => $data['fecha_hora'],
            ':s'  => $data['servicio'],
            ':id' => $data['id'],
        ]);
    }

    /**
     * 🟢 NUEVA FUNCIÓN GENÉRICA: Actualiza el estado de la cita.
     * Maneja 'Cancelada', 'Completada', etc.
     */
    public static function updateStatus($id, $estado) {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE citas
            SET estado = :estado
            WHERE id = :id
        ");
        return $stmt->execute([
            ':estado' => $estado, 
            ':id' => $id,
        ]);
    }
    
    /**
     * Verifica si existe otra cita en la misma fecha y hora.
     */
    public static function checkOverlap($fecha_hora, $except_id = null) {
        global $pdo;
        // CORRECCIÓN: Usar 'Agendada' (estado de Cita::create)
        $sql = "SELECT COUNT(*) FROM citas WHERE fecha_hora = :fh AND estado = 'Agendada'";
        
        $params = [':fh' => $fecha_hora];
        
        if ($except_id) {
            $sql .= " AND id != :except_id";
            $params[':except_id'] = $except_id;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
}