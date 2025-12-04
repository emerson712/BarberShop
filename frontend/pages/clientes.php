<?php
require_once __DIR__ . '/../../backend/core/session.php';
require_login_page();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Clientes - Gestor Barbería</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

  <link rel="stylesheet" href="../assets/css/style.css">

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
</head>
<body class="clientes-page">

<?php include '../partials/navbar.php'; ?>

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Listado de clientes</h3>
    <button class="btn btn-success" id="btnNuevo">Nuevo cliente</button>
  </div>

  <table id="tablaClientes" class="table table-striped" style="width:100%">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Teléfono</th>
        <th>Email</th>
        <th>Notas/Prefs.</th>
        <th>Acciones</th>
      </tr>
    </thead>
  </table>
</div>

<div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formCliente" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModal">Nuevo cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="cliente_id" name="id">

        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" class="form-control" name="nombre" id="nombre" required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Teléfono</label>
          <input type="text" class="form-control" name="telefono" id="telefono">
        </div>

        <div class="mb-3">
          <label class="form-label">Email (Opcional)</label>
          <input type="email" class="form-control" name="email" id="email">
        </div>

        <div class="mb-3">
          <label class="form-label">Notas / Preferencias de Corte</label>
          <textarea class="form-control" name="notas" id="notas" rows="3"></textarea>
        </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function() {

  var tabla = $('#tablaClientes').DataTable({
    ajax: {
      url: '../../backend/api/clientes.php?action=list',
      dataSrc: 'data'
    },

language: {
    url: '../assets/js/es-ES.json' 
},

    columns: [
      { data: 'id' },
      { data: 'nombre' },
      { data: 'telefono' }, // Reordenamos Teléfono
      { data: 'email' },
      { data: 'notas' }, // Columna Notas añadida
      {
        data: null,
        orderable: false,
        render: function(data, type, row) {
          return `
            <button class="btn btn-sm btn-primary btn-editar" data-id="${row.id}">Editar</button>
            <button class="btn btn-sm btn-danger btn-eliminar" data-id="${row.id}">Eliminar</button>
          `;
        }
      }
    ]
  });

  $('#btnNuevo').on('click', function() {
    $('#tituloModal').text('Nuevo cliente');
    $('#formCliente')[0].reset();
    $('#cliente_id').val('');
    $('#modalCliente').modal('show');
  });

  $('#formCliente').on('submit', function(e) {
    e.preventDefault();
    var id = $('#cliente_id').val();
    var action = id ? 'update' : 'create';

    $.ajax({
      url: '../../backend/api/clientes.php?action=' + action,
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          $('#modalCliente').modal('hide');
          tabla.ajax.reload(null, false);
        } else {
          alert(resp.message || 'Ocurrió un error');
        }
      },
      error: function() {
        alert('Error en la petición AJAX');
      }
    });
  });

  $('#tablaClientes').on('click', '.btn-editar', function() {
    var id = $(this).data('id');

    $.ajax({
      url: '../../backend/api/clientes.php?action=get&id=' + id,
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          var c = resp.cliente;
          $('#cliente_id').val(c.id);
          $('#nombre').val(c.nombre);
          $('#email').val(c.email);
          $('#telefono').val(c.telefono);
          $('#notas').val(c.notas); // Cargar valor de Notas
          $('#tituloModal').text('Editar cliente');
          $('#modalCliente').modal('show');
        } else {
          alert(resp.message || 'No se encontró el cliente');
        }
      },
      error: function() {
        alert('Error en la petición AJAX');
      }
    });
  });

  $('#tablaClientes').on('click', '.btn-eliminar', function() {
    if (!confirm('¿Seguro que desea eliminar este cliente?')) return;

    var id = $(this).data('id');

    $.ajax({
      url: '../../backend/api/clientes.php?action=delete',
      method: 'POST',
      data: { id: id },
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          tabla.ajax.reload(null, false);
        } else {
          alert(resp.message || 'No se pudo eliminar');
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