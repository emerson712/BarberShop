# Proyecto: Gestor de Clientes (CRUD completo con PHP, PostgreSQL y AJAX)

## Contexto

La empresa ficticia **"Mi Pyme S.A.S."** necesita un pequeño sistema web para gestionar
la información de sus clientes. El sistema será utilizado por el área comercial para:

- Registrar nuevos clientes
- Actualizar datos de contacto
- Consultar listados filtrables y ordenables
- Eliminar registros que ya no sean necesarios

El sistema debe estar desarrollado como una aplicación web clásica usando:

- **Frontend:** HTML, CSS, Bootstrap, jQuery, DataTables
- **Backend:** PHP con variables de sesión, organizado en carpetas
- **Base de datos:** PostgreSQL
- **Comunicación:** AJAX (sin recargar la página en las operaciones CRUD)

---

## Requerimientos funcionales

1. **Autenticación**
   - Debe existir una pantalla de login.
   - Solo usuarios autenticados pueden acceder al módulo de clientes.
   - Las credenciales se validan contra la tabla `usuarios` en PostgreSQL.
   - Debe utilizarse manejo de **sesión en PHP**.

2. **Módulo de clientes**
   - Listado de clientes en una tabla interactiva:
     - Búsqueda
     - Ordenamiento
     - Paginación
   - La tabla debe implementarse con **DataTables**.
   - Debe permitir:
     - Crear un nuevo cliente
     - Editar un cliente existente
     - Eliminar un cliente
   - Todas las operaciones se deben realizar vía **AJAX**, consumiendo APIs en PHP.

3. **Múltiples páginas**
   - Debe existir al menos:
     - `login.php`: pantalla de autenticación.
     - `clientes.php`: módulo principal de clientes.
     - `about.php`: página informativa de la aplicación.
   - Todas las páginas internas deben incluir un **navbar** común (parcial/plantilla).

4. **Arquitectura**
   - Debe existir una separación explícita entre:
     - `frontend/` (páginas, assets)
     - `backend/` (API, modelos, controladores, configuración)
   - El backend debe exponer endpoints en `/backend/api/*.php`.
   - Las consultas a la base de datos se deben realizar usando **PDO**.

---

## Requerimientos no funcionales

- Código organizado y comentado.
- Nombres de carpetas y archivos coherentes.
- Manejo básico de errores (por ejemplo, mostrar mensajes si falla el login o el CRUD).
- Validaciones mínimas en los formularios (ej. nombre obligatorio).

---

## Entregables

1. Código fuente completo del proyecto con la estructura de carpetas organizada.
2. Script SQL para crear la base de datos y tablas necesarias.
3. Breve documento (1-2 páginas) que explique:
   - Estructura de carpetas
   - Flujo de autenticación
   - Flujo del CRUD de clientes
