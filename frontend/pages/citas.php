<?php
require_once __DIR__ . '/../../backend/core/session.php';
// Requerimos el modelo Cliente para listar los clientes en el formulario
require_once __DIR__ . '/../../backend/models/Cliente.php'; 
require_login_page();

// Obtener la lista de clientes para el select del formulario de citas
$clientes = Cliente::all(); 
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Citas - Gestor Barbería</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
</head>
<body class="citas-page">

<?php include '../partials/navbar.php'; ?>

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Gestión de Citas</h3>
    <button class="btn btn-success" id="btnNuevaCita">Agendar Cita</button>
  </div>

  <table id="tablaCitas" class="table table-striped" style="width:100%">
    <thead>
      <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Fecha/Hora</th>
        <th>Servicio</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
  </table>
</div>

<div class="modal fade" id="modalCita" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formCita" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModal">Agendar Nueva Cita</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="cita_id" name="id">

        <div class="mb-3">
          <label class="form-label">Cliente</label>
          <select class="form-select" name="cliente_id" id="cliente_id" required>
            <option value="">Seleccione un cliente</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?php echo $cliente['id']; ?>"><?php echo htmlspecialchars($cliente['nombre']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Fecha</label>
          <input type="date" class="form-control" name="fecha" id="fecha" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Hora</label>
          <input type="time" class="form-control" name="hora" id="hora" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Servicio</label>
          <select class="form-select" name="servicio" id="servicio" required>
            </select>
        </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar Cita</button>
      </div>
    </form>
  </div>
</div>

<script>
/**
 * Carga la lista de servicios desde la API y popula el desplegable.
 * @param {string|null} selectedService Nombre del servicio a seleccionar (usado en Editar).
 */
function loadServices(selectedService = null) {
    var select = $('#servicio');
    // Limpia y añade la opción predeterminada
    select.empty().append('<option value="">Seleccione un servicio</option>'); 

    $.ajax({
        url: '../../backend/api/servicios.php?action=list',
        dataType: 'json',
        success: function(resp) {
            if (resp.success && resp.data) {
                // Iterar sobre los servicios obtenidos de la base de datos
                $.each(resp.data, function(index, servicio) {
                    var option = $('<option></option>')
                        // Usamos el nombre del servicio como valor, tal como lo guardamos en Cita.php
                        .attr('value', servicio.nombre) 
                        .text(servicio.nombre + ' ($' + parseFloat(servicio.precio).toFixed(2) + ')'); // Mostrar precio en el desplegable
                        
                    // Si estamos editando, seleccionamos el servicio actual
                    if (selectedService && servicio.nombre === selectedService) {
                        option.attr('selected', 'selected');
                    }
                    
                    select.append(option);
                });
            } else {
                console.error('No se pudieron cargar los servicios de la base de datos.');
            }
        },
        error: function() {
            alert('Error al intentar cargar la lista de servicios. Verifique la API.');
        }
    });
}


$(document).ready(function() {

  // Cargar servicios al iniciar la página para que estén listos
  loadServices();

  var tabla = $('#tablaCitas').DataTable({
    ajax: {
      url: '../../backend/api/citas.php?action=list',
      dataSrc: 'data'
    },
language: {
    url: '../assets/js/es-ES.json' 
},
    columns: [
      { data: 'id' },
      { data: 'cliente_nombre' },
      { 
        data: 'fecha_hora',
        render: function(data, type, row) {
          // Muestra hasta los minutos (ej: 2025-01-01 10:00)
          return data.substring(0, 16); 
        }
      },
      { data: 'servicio' },
      { 
        data: 'estado',
        render: function(data, type, row) {
          // Colorea el estado para mejor visibilidad
          const estado = data.trim(); // Limpiamos espacios por seguridad
          if (estado === 'Agendada' || estado === 'Confirmada') {
            return '<span class="badge bg-primary">Agendada</span>';
          } else if (estado === 'Cancelada') {
            return '<span class="badge bg-danger">Cancelada</span>';
          } else if (estado === 'Completada') { 
            return '<span class="badge bg-success">Completada</span>';
          }
          return data;
        }
      },
      {
        data: null,
        orderable: false,
        render: function(data, type, row) {
          const estado_activo = row.estado ? row.estado.trim() : ''; 
          var buttons = ''; 
          
          if (estado_activo === 'Agendada' || estado_activo === 'Confirmada') {
            
            // Usamos d-flex y gap-1 para asegurar la separación de forma robusta
            buttons += `<div class="d-flex gap-1">`; 

            // Botón Editar
            buttons += `<button class="btn btn-sm btn-primary btn-editar" data-id="${row.id}">Editar</button>`;
            
            // Botón Completada
            buttons += `<button class="btn btn-sm btn-success btn-completar" data-id="${row.id}">Completada</button>`;
            
            // Botón Cancelar
            buttons += `<button class="btn btn-sm btn-danger btn-cancelar" data-id="${row.id}">Cancelar</button>`;

            buttons += `</div>`; // Cerramos el div d-flex
          }
          
          return buttons;
        }
      }
    ]
  });

  // Lógica para abrir el modal de nueva cita
  $('#btnNuevaCita').on('click', function() {
    $('#tituloModal').text('Agendar Nueva Cita');
    $('#formCita')[0].reset();
    $('#cita_id').val('');
    
    // 🟢 Cargar los servicios antes de mostrar el modal
    loadServices(); 
    
    $('#modalCita').modal('show');
  });

  // Lógica para Crear/Actualizar Cita
  $('#formCita').on('submit', function(e) {
    e.preventDefault();
    var id = $('#cita_id').val();
    var action = id ? 'update' : 'create';

    var fecha = $('#fecha').val();
    var hora = $('#hora').val();
    var postData = $(this).serialize() + '&fecha=' + fecha + '&hora=' + hora;


    $.ajax({
      url: '../../backend/api/citas.php?action=' + action,
      method: 'POST',
      data: postData,
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          $('#modalCita').modal('hide');
          tabla.ajax.reload(null, false);
        } else {
          alert(resp.message || 'Ocurrió un error al guardar la cita');
        }
      },
      error: function() {
        alert('Error en la petición AJAX');
      }
    });
  });

  // Lógica para Editar una cita
  $('#tablaCitas').on('click', '.btn-editar', function() {
    var id = $(this).data('id');

    $.ajax({
      url: '../../backend/api/citas.php?action=get&id=' + id,
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          var c = resp.cita;
          $('#cita_id').val(c.id);
          $('#cliente_id').val(c.cliente_id);
          
          // 🟢 Cargar servicios y seleccionar el actual al mismo tiempo
          // La función loadServices se encargará de seleccionar el valor correcto después de cargar las opciones.
          loadServices(c.servicio); 
          
          // Dividir la fecha y hora para los campos del formulario
          const [fecha, tiempo] = c.fecha_hora.split(' ');
          const hora = tiempo.substring(0, 5); // Obtener solo HH:MM
          
          $('#fecha').val(fecha);
          $('#hora').val(hora);

          $('#tituloModal').text('Editar Cita');
          $('#modalCita').modal('show');
        } else {
          alert(resp.message || 'No se encontró la cita');
        }
      },
      error: function() {
        alert('Error en la petición AJAX');
      }
    });
  });

  // Lógica para Cancelar una cita
  $('#tablaCitas').on('click', '.btn-cancelar', function() {
    if (!confirm('¿Seguro que desea CANCELAR esta cita?')) return;

    var id = $(this).data('id');

    $.ajax({
      url: '../../backend/api/citas.php?action=cancel', 
      method: 'POST',
      data: { id: id },
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          tabla.ajax.reload(null, false);
        } else {
          alert(resp.message || 'No se pudo cancelar la cita');
        }
      },
      error: function() {
        alert('Error en la petición AJAX');
      }
    });
  });

  // Lógica para Completar una cita 
  $('#tablaCitas').on('click', '.btn-completar', function() {
    if (!confirm('¿Marcar esta cita como Completada/Atendida?')) return;

    var id = $(this).data('id');

    $.ajax({
      url: '../../backend/api/citas.php?action=complete', 
      method: 'POST',
      data: { id: id },
      dataType: 'json',
      success: function(resp) {
        if (resp.success) {
          tabla.ajax.reload(null, false);
        } else {
          alert(resp.message || 'No se pudo completar la cita');
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