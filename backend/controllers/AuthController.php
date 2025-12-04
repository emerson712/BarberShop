<?php
require_once __DIR__ . '/../core/session.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {

    public static function login($username, $password) {
        $username = trim($username);
        $password = trim($password);

        if ($username === '' || $password === '') {
            return [false, 'Debe ingresar usuario y contraseña'];
        }

        $user = Usuario::findByUsername($username);
        if (!$user) {
            return [false, 'Usuario o contraseña incorrectos'];
        }

        // Verificar hash de contraseña (usando crypt + pgcrypto)
        if (!hash_equals($user['password_hash'], crypt($password, $user['password_hash']))) {
            return [false, 'Usuario o contraseña incorrectos'];
        }

        // Autenticación correcta
        $_SESSION['usuario_id']     = $user['id'];
        $_SESSION['usuario_nombre'] = $user['nombre'];
        $_SESSION['usuario_user']   = $user['username'];

        return [true, 'Inicio de sesión correcto'];
    }

    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
    }
}
