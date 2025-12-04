<?php
require_once __DIR__ . '/../core/session.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../controllers/CitaController.php';

// Asegura que solo usuarios autentados accedan a las APIs
require_login_api();

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'list':
        $citas = CitaController::listar();
        json_success(['data' => $citas]);
        break;

    case 'get':
        $id = (int)($_GET['id'] ?? 0);
        $cita = CitaController::obtener($id);
        if ($cita) {
            json_success(['cita' => $cita]);
        } else {
            json_error('Cita no encontrada', 404);
        }
        break;

    case 'create':
        // Renombramos la acción en el controlador para reflejar el negocio (agendar)
        list($ok, $msg) = CitaController::agendar($_POST); 
        if ($ok) {
            json_success(['message' => $msg]);
        } else {
            json_error($msg);
        }
        break;

    case 'update':
        list($ok, $msg) = CitaController::actualizar($_POST);
        if ($ok) {
            json_success(['message' => $msg]);
        } else {
            json_error($msg);
        }
        break;

    case 'cancel':
        $id = (int)($_POST['id'] ?? 0);
        list($ok, $msg) = CitaController::cancelar($id);
        if ($ok) {
            json_success(['message' => $msg]);
        } else {
            json_error($msg);
        }
        break;
        
    case 'complete': // 🟢 NUEVO CASO para marcar como completada/atendida
        $id = (int)($_POST['id'] ?? 0);
        list($ok, $msg) = CitaController::marcarCompletada($id);
        if ($ok) {
            json_success(['message' => $msg]);
        } else {
            json_error($msg);
        }
        break;

    default:
        json_error('Acción no válida', 400);
}