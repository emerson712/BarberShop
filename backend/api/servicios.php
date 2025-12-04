<?php
// backend/api/servicios.php

require_once __DIR__ . '/../core/session.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../controllers/ServicioController.php';

// Asegura que solo usuarios autenticados accedan a las APIs
require_login_api();

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'list':
        $servicios = ServicioController::listar();
        json_success(['data' => $servicios]);
        break;

    case 'get':
        $id = (int)($_GET['id'] ?? 0);
        $servicio = ServicioController::obtener($id);
        if ($servicio) {
            json_success(['servicio' => $servicio]);
        } else {
            json_error('Servicio no encontrado', 404);
        }
        break;

    case 'create':
        list($ok, $msg) = ServicioController::crear($_POST);
        if ($ok) {
            json_success(['message' => $msg]);
        } else {
            json_error($msg);
        }
        break;

    case 'update':
        list($ok, $msg) = ServicioController::actualizar($_POST);
        if ($ok) {
            json_success(['message' => $msg]);
        } else {
            json_error($msg);
        }
        break;

    case 'delete':
        $id = (int)($_POST['id'] ?? 0);
        list($ok, $msg) = ServicioController::eliminar($id);
        if ($ok) {
            json_success(['message' => $msg]);
        } else {
            json_error($msg);
        }
        break;

    default:
        json_error('Acción no válida', 400);
}