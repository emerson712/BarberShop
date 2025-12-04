# Proyecto: Gestor de Barbería (Clientes, Servicios y Citas)

## Contexto

La **Barbería "BarberShop"** necesita un sistema web para centralizar sus operaciones. 
El sistema debe ser capaz de gestionar la información de los clientes, mantener un 
catálogo de servicios con sus precios, y permitir el agendamiento y seguimiento de citas de manera eficiente.

El desarrollo se realiza bajo una arquitectura web clásica y robusta, utilizando las siguientes tecnologías:

* **Frontend:** HTML, CSS, **Bootstrap 5**, jQuery, **DataTables**.
* **Backend:** **PHP** con manejo de sesión, organizado en capas (Modelo-Controlador-API).
* **Base de datos:** **PostgreSQL** (incluyendo funciones de cifrado).
* **Comunicación:** **AJAX** (para todas las operaciones, sin recarga de página).

---

## Requerimientos Funcionales

### 1. Autenticación y Seguridad

* **Login:** Debe existir una pantalla de autenticación (`login.php`).
* **Sesiones:** El acceso a cualquier módulo interno debe estar protegido por **variables de sesión de PHP**.
* **Seguridad:** La validación de credenciales se realiza contra la tabla `usuarios` en PostgreSQL.

### 2. Módulo de Clientes

* **Página:** `clientes.php`.
* **Funcionalidad:** Implementación de un CRUD completo (Crear, Leer, Actualizar, Eliminar).
* **Interfaz:** Listado interactivo mediante **DataTables** (búsqueda, ordenamiento, paginación, traducido al español).
* **Operaciones:** Todas las operaciones deben ser manejadas con peticiones **AJAX**.

### 3. Módulo de Servicios

* **Página:** `servicios.php`.
* **Funcionalidad:** Gestión del catálogo de servicios ofrecidos por la barbería.
* **Datos:** Cada servicio debe incluir un **precio** (`precio`) asociado.
* **Operaciones:** CRUD completo vía **AJAX**.

### 4. Módulo de Citas

* **Página:** `citas.php`.
* **Agendamiento:** Debe permitir registrar una nueva cita, seleccionando obligatoriamente un **Cliente** y un **Servicio** de los listados existentes.
* **Precio:** El precio de la cita (`precio_cobrado`) debe ser capturado automáticamente del servicio seleccionado al momento de agendar.
* **Validación de Disponibilidad:** El sistema debe impedir agendar dos citas en el mismo *slot* de **Fecha y Hora**.
* **Manejo de Estados:** La aplicación debe gestionar los siguientes estados de la cita:
    * `Agendada` (Estado por defecto).
    * `Cancelada` (Acción disponible).
    * `Completada` (Acción disponible para marcar la cita como realizada).

### 5. Arquitectura del Software

* **Organización:** Estructura de carpetas clara (`frontend/`, `backend/`, `backend/models`, `backend/controllers`, `backend/api`).
* **Comunicación:** El backend debe exponer endpoints claros en `/backend/api/*.php` para la comunicación AJAX.
* **Base de Datos:** Uso estricto de **PDO** para la conexión y ejecución de consultas en PostgreSQL.

---

## Requerimientos No Funcionales
* El código debe ser organizado, limpio y comentado.
* Manejo de errores básico para informar al usuario sobre fallas en el login, validaciones o el CRUD.
* Solución implementada para el error **CORS** de DataTables, sirviendo el archivo de traducción JSON localmente.
* Uso de `require_once` y parciales (`navbar.php`) para mantener la coherencia del diseño.

---

## Entregables
1.  Código fuente completo del proyecto.
2.  Script **SQL** final para crear todas las tablas necesarias (`usuarios`, `clientes`, `servicios`, `citas`).