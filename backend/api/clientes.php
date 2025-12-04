<?php
require_once __DIR__ . '/../core/session.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../controllers/ClienteController.php';

// Asegura que solo usuarios autenticados accedan a las APIs
require_login_api();

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'list':
        $clientes = ClienteController::listar();
        json_success(['data' => $clientes]);
        break;

    case 'get':
        $id = (int)($_GET['id'] ?? 0);
        $cliente = ClienteController::obtener($id);
        if ($cliente) {
            json_success(['cliente' => $cliente]);
        } else {
            json_error('Cliente no encontrado', 404);
        }
        break;

    case 'create':
        list($ok, $msg) = ClienteController::crear($_POST);
        if ($ok) {
            json_success(['message' => $msg]);
        } else {
            json_error($msg);
        }
        break;

    case 'update':
        list($ok, $msg) = ClienteController::actualizar($_POST);
        if ($ok) {
            json_success(['message' => $msg]);
        } else {
            json_error($msg);
        }
        break;

    case 'delete':
        $id = (int)($_POST['id'] ?? 0);
        list($ok, $msg) = ClienteController::eliminar($id);
        if ($ok) {
            json_success(['message' => $msg]);
        } else {
            json_error($msg);
        }
        break;

    default:
        json_error('Acción no válida', 400);
}
