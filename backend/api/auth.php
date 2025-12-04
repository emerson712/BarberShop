<?php
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../core/session.php';

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'login':
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        list($ok, $msg) = AuthController::login($username, $password);
        if ($ok) {
            json_success(['message' => $msg]);
        } else {
            json_error($msg, 401);
        }
        break;

    case 'logout':
        AuthController::logout();
        json_success(['message' => 'Sesión cerrada']);
        break;

    default:
        json_error('Acción no válida', 400);
}
