<?php
// backend/controllers/ServicioController.php

require_once __DIR__ . '/../models/Servicio.php';

class ServicioController {

    public static function listar() {
        return Servicio::all();
    }

    public static function obtener($id) {
        return Servicio::find($id);
    }

    public static function crear($data) {
        if (trim($data['nombre'] ?? '') === '') {
            return [false, 'El nombre del servicio es obligatorio.'];
        }
        if (!is_numeric($data['precio']) || $data['precio'] < 0) {
            return [false, 'El precio debe ser un número positivo.'];
        }
        
        Servicio::create($data);
        return [true, 'Servicio creado correctamente.'];
    }

    public static function actualizar($data) {
        if (empty($data['id']) || trim($data['nombre'] ?? '') === '') {
            return [false, 'Datos inválidos o nombre obligatorio.'];
        }
        if (!is_numeric($data['precio']) || $data['precio'] < 0) {
            return [false, 'El precio debe ser un número positivo.'];
        }

        Servicio::update($data);
        return [true, 'Servicio actualizado correctamente.'];
    }

    public static function eliminar($id) {
        if (!$id) {
            return [false, 'ID inválido.'];
        }
        
        // Aquí podrías añadir una validación para ver si el servicio tiene citas agendadas
        
        Servicio::delete($id);
        return [true, 'Servicio eliminado correctamente.'];
    }
}