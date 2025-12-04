<?php
require_once __DIR__ . '/../models/Cita.php';

class CitaController {

    public static function listar() {
        return Cita::all();
    }

    public static function obtener($id) {
        return Cita::find($id);
    }

    /**
     * Lógica para agendar (crear) una cita.
     */
    public static function agendar($data) {
        // Validación básica de campos
        if (empty($data['cliente_id']) || empty($data['fecha']) || empty($data['hora']) || trim($data['servicio'] ?? '') === '') {
            return [false, 'Faltan campos obligatorios para la cita.'];
        }

        $fecha_hora = $data['fecha'] . ' ' . $data['hora'] . ':00';
        $data['fecha_hora'] = $fecha_hora;

        // 1. Verificar si la hora está en el pasado
        if (strtotime($fecha_hora) < time()) {
            return [false, 'No se puede agendar una cita en el pasado.'];
        }

        // 2. Verificar disponibilidad
        if (Cita::checkOverlap($fecha_hora)) {
            return [false, 'Esa hora ya está reservada. Por favor, elija otro horario.'];
        }

        Cita::create($data);
        return [true, 'Cita agendada correctamente.'];
    }

    /**
     * Lógica para actualizar (reprogramar) una cita.
     */
    public static function actualizar($data) {
        // Validación básica de campos
        if (empty($data['id']) || empty($data['cliente_id']) || empty($data['fecha']) || empty($data['hora']) || trim($data['servicio'] ?? '') === '') {
            return [false, 'Datos inválidos para la actualización.'];
        }

        $fecha_hora = $data['fecha'] . ' ' . $data['hora'] . ':00';
        $data['fecha_hora'] = $fecha_hora;

        // 1. Verificar si la hora está en el pasado
        if (strtotime($fecha_hora) < time()) {
            return [false, 'No se puede reprogramar una cita en el pasado.'];
        }

        // 2. Verificar disponibilidad (excluyendo la cita actual)
        if (Cita::checkOverlap($fecha_hora, $data['id'])) {
            return [false, 'Esa hora ya está reservada. Por favor, elija otro horario.'];
        }
        
        Cita::update($data);
        return [true, 'Cita actualizada correctamente.'];
    }

    /**
     * Lógica para cancelar una cita.
     */
    public static function cancelar($id) {
        if (!$id) {
            return [false, 'ID inválido'];
        }
        // MODIFICADO: Usa la función genérica updateStatus
        Cita::updateStatus($id, 'Cancelada');
        return [true, 'Cita cancelada correctamente.'];
    }

    /**
     * 🟢 NUEVA FUNCIÓN: Lógica para marcar una cita como completada.
     */
    public static function marcarCompletada($id) {
        if (!$id) {
            return [false, 'ID de cita inválido'];
        }
        // Usa la función genérica updateStatus con el estado 'Completada'
        Cita::updateStatus($id, 'Completada'); 
        return [true, 'Cita marcada como completada.'];
    }
}