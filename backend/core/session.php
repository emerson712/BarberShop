<?php
// Manejo centralizado de sesiones

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica que exista un usuario logueado para APIs.
 * Si no hay sesión, responde JSON 401 y termina la ejecución.
 */
function require_login_api() {
    if (!isset($_SESSION['usuario_id'])) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => 'No autorizado. Inicie sesión nuevamente.'
        ]);
        exit;
    }
}

/**
 * Verifica que exista sesión para páginas.
 * Si no hay sesión, redirige a login.
 */
function require_login_page() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: ../../frontend/pages/login.php");
        exit;
    }
}
