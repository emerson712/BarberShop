<?php
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {

    public static function listar() {
        return Cliente::all();
    }

    public static function obtener($id) {
        return Cliente::find($id);
    }

    public static function crear($data) {
        if (trim($data['nombre'] ?? '') === '') {
            return [false, 'El nombre es obligatorio'];
        }
        Cliente::create($data);
        return [true, 'Cliente creado correctamente'];
    }

    public static function actualizar($data) {
        if (empty($data['id']) || trim($data['nombre'] ?? '') === '') {
            return [false, 'Datos inválidos'];
        }
        Cliente::update($data);
        return [true, 'Cliente actualizado correctamente'];
    }

    public static function eliminar($id) {
        if (!$id) {
            return [false, 'ID inválido'];
        }
        Cliente::delete($id);
        return [true, 'Cliente eliminado correctamente'];
    }
}
