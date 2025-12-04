<?php
// frontend/pages/servicios.php

require_once __DIR__ . '/../../backend/core/session.php';
require_login_page();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Servicios - Gestor Barbería</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
</head>
<body class="servicios-page">

<?php include '../partials/navbar.php'; ?>

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Gestión de Servicios</h3>
    <button class="btn btn-success" id="btnNuevoServicio">Crear Nuevo Servicio</button>
  </div>

  <table id="tablaServicios" class="table table-striped" style="width:100%">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Duración (min)</th>
        <th>Acciones</th>
      </tr>
    </thead>
  </table>
</div>

<div class="modal fade" id="modalServicio" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formServicio" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModal">Crear Nuevo Servicio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="servicio_id" name="id">

        <div class="mb-3">
          <label class="form-label">Nombre del Servicio</label>
          <input type="text" class="form-control" name="nombre" id="nombre" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Precio ($)</label>
          <input type="number" step="0.01" class="form-control" name="precio" id="precio" required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Duración Estimada (Minutos)</label>
          <input type="number" class="form-control" name="duracion_minutos" id="duracion_minutos" required>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar Servicio</button>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function() {

  var tabla = $('#tablaServicios').DataTable({
    ajax: {
      url: '../../backend/api/servicios.php?action=list',
      dataSrc: 'data'
    },
language: {
    url: '../assets/js/es-ES.json' 
},
    columns: [
      { data: 'id' },
      { data: 'nombre' },
      { 
        data: 'precio',
        // Formatear como moneda
        render: function(data, type, row) {
            return '$' + parseFloat(data).toFixed(2);
        }
      },
      { data: 'duracion_minutos' },
      {
        data: null,
        orderable: false,
        render: function(data, type, row) {
          return `
            <button class="btn btn-sm btn-primary btn-editar" data-id="${row.id}">Editar</button>
            <button class="btn btn-sm btn-danger btn-eliminar ms-1" data-id="${row.id}">Eliminar</button>
          `;
        }
      }
    ]
  });

  // Lógica para abrir el modal de nuevo servicio
  $('#btnNuevoServicio').on('click', function() {
    $('#tituloModal').text('Crear Nuevo Servicio');
    $('#formServicio')[0].reset();
    $('#servicio_id').val('');
    $('#modalServicio').modal('show');
  });

  // Lógica para Crear/Actualizar Servicio
  $('#formServicio').on('submit', function(e) {
    e.preventDefault();
    var id = $('#servicio_id').val();
    var action = id ? 'update' : 'create';

    $.ajax({
      url: '../../backend/api/servicios.php?action=' + action,
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          $('#modalServicio').modal('hide');
          tabla.ajax.reload(null, false);
        } else {
          alert(resp.message || 'Ocurrió un error al guardar el servicio');
        }
      },
      error: function() {
        alert('Error en la petición AJAX');
      }
    });
  });

  // Lógica para Editar un servicio
  $('#tablaServicios').on('click', '.btn-editar', function() {
    var id = $(this).data('id');

    $.ajax({
      url: '../../backend/api/servicios.php?action=get&id=' + id,
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          var s = resp.servicio;
          $('#servicio_id').val(s.id);
          $('#nombre').val(s.nombre);
          $('#precio').val(parseFloat(s.precio).toFixed(2));
          $('#duracion_minutos').val(s.duracion_minutos);
          $('#tituloModal').text('Editar Servicio');
          $('#modalServicio').modal('show');
        } else {
          alert(resp.message || 'No se encontró el servicio');
        }
      },
      error: function() {
        alert('Error en la petición AJAX');
      }
    });
  });

  // Lógica para Eliminar un servicio
  $('#tablaServicios').on('click', '.btn-eliminar', function() {
    if (!confirm('¿Seguro que desea eliminar este servicio?')) return;

    var id = $(this).data('id');

    $.ajax({
      url: '../../backend/api/servicios.php?action=delete',
      method: 'POST',
      data: { id: id },
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          tabla.ajax.reload(null, false);
        } else {
          alert(resp.message || 'No se pudo eliminar el servicio');
        }
      },
      error: function() {
        alert('Error en la petición AJAX');
      }
    });
  });

});
</script>

</body>
</html>