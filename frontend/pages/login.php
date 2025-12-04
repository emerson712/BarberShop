<?php
require_once __DIR__ . '/../../backend/core/session.php';

// Si ya está autenticado, redirige a clientes
if (isset($_SESSION['usuario_id'])) {
    header("Location: clientes.php");
    exit;
}

$error = $_GET['error'] ?? '';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Login - Gestor Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css"> 
</head>
<body class="login-page">

<div class="login-container"> 
    <div class="card shadow">
        <div class="card-header text-center">
            <h5>Iniciar sesión</h5>
        </div>
        <div class="card-body">
            <div id="alerta" class="alert alert-danger d-none"></div>
            <form id="formLogin">
                <div class="mb-3">
                    <label class="form-label">Usuario</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function() {
    $('#formLogin').on('submit', function(e) {
        e.preventDefault();
        $('#alerta').addClass('d-none').text('');

        $.ajax({
            url: '../../backend/api/auth.php?action=login',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    window.location.href = 'clientes.php';
                } else {
                    $('#alerta').removeClass('d-none').text(resp.message || 'Error al iniciar sesión');
                }
            },
            error: function() {
                $('#alerta').removeClass('d-none').text('Error de comunicación con el servidor');
            }
        });
    });
});
</script>

</body>
</html>