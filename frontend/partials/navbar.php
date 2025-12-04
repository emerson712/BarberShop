<?php
// frontend/partials/navbar.php

require_once __DIR__ . '/../../backend/core/session.php';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="clientes.php">Gestor Barbería</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav"
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        
        <li class="nav-item">
          <a class="nav-link" href="clientes.php">Clientes</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="citas.php">Citas</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="servicios.php">Servicios</a>
        </li>

      </ul>
      <span class="navbar-text me-3">
        <?php if (isset($_SESSION['usuario_nombre'])): ?>
          Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
        <?php endif; ?>
      </span>
      <a href="#" id="btnLogout" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
    </div>
  </div>
</nav>

<script>
// Logout vía AJAX para mantener el patrón API
document.addEventListener('DOMContentLoaded', function() {
  const btn = document.getElementById('btnLogout');
  if (btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      fetch('../../backend/api/auth.php?action=logout', {
        method: 'POST'
      })
      .then(r => r.json())
      .then(() => {
        // Redirige al login.php, que es la página que controla la autenticación
        window.location.href = 'login.php'; 
      });
    });
  }
});
</script>