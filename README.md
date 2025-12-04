# Guía paso a paso para implementar el proyecto en clase

Esta guía está pensada para que el estudiante pueda desplegar el proyecto

---

## Paso 1. Preparar la base de datos en PostgreSQL

1. Crear la base de datos, por ejemplo:

   ```sql
   CREATE DATABASE webprog;
   CREATE EXTENSION IF NOT EXISTS pgcrypto;
   ```

2. Crear la tabla de usuarios y un usuario de prueba:

   ```sql
   CREATE TABLE usuarios (
       id SERIAL PRIMARY KEY,
       username VARCHAR(50) UNIQUE NOT NULL,
       password_hash TEXT NOT NULL,
       nombre VARCHAR(100) NOT NULL
   );

   INSERT INTO usuarios(username, password_hash, nombre)
   VALUES (
       'admin',
       crypt('admin123', gen_salt('bf')),
       'Administrador'
   );
   ```

3. Crear la tabla de clientes:

   ```sql
   CREATE TABLE clientes (
       id SERIAL PRIMARY KEY,
       nombre VARCHAR(100) NOT NULL,
       email VARCHAR(100),
       telefono VARCHAR(50),
       ciudad VARCHAR(100),
       creado_en TIMESTAMP DEFAULT now()
   );
   ```

---

## Paso 2. Configurar el backend (PHP + PDO)

1. Configurar `backend/config/database.php` con las credenciales reales de PostgreSQL.
2. Recordar:
   - Manejo de excepciones con `try/catch`
   - Por qué usar `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`.

3. Revisar los modelos:
   - `Usuario.php` para el login.
   - `Cliente.php` para el CRUD.

---

## Paso 3. Manejo de sesiones

1. Revisar `backend/core/session.php`:
   - `session_start()`
   - Funciones `require_login_api()` y `require_login_page()`.

2. Revisar el flujo:
   - El login guarda datos en `$_SESSION`.
   - Las APIs usan `require_login_api()` para protegerse.
   - Las páginas internas usan `require_login_page()` para redirigir al login.

---

## Paso 4. Implementar la autenticación (login/logout)

1. Ver `backend/controllers/AuthController.php`:
   - Validación de usuario y contraseña.
   - Uso de `crypt()` para verificar el hash.

2. Ver `backend/api/auth.php`:
   - Endpoint `action=login` para procesar el formulario vía AJAX.
   - Endpoint `action=logout` para cerrar sesión.

3. Revisar `frontend/pages/login.php`:
   - Formulario de login con Bootstrap.
   - Envío del formulario con jQuery AJAX.

---

## Paso 5. Implementar el módulo de clientes (CRUD)

1. Revisar `backend/models/Cliente.php`:
   - Métodos `all`, `find`, `create`, `update`, `delete`.

2. Revisar `backend/controllers/ClienteController.php`:
   - Cómo encapsula la lógica de validación.

3. Revisar `backend/api/clientes.php`:
   - Acciones `list`, `get`, `create`, `update`, `delete`.

4. Revisar `frontend/pages/clientes.php`:
   - Tabla inicializada con **DataTables** y fuente AJAX.
   - Modales de Bootstrap para crear/editar.
   - Botones de editar y eliminar que disparan peticiones AJAX.

---

## Paso 6. Separación frontend/backend y uso de parciales

1. Ecplorar la estructura de carpetas:
   - `frontend/pages`
   - `frontend/partials`
   - `backend/api`, `backend/models`, etc.

2. Revisar `frontend/partials/navbar.php`:
   - Uso de la sesión para mostrar el nombre del usuario.
   - Botón de logout que llama a la API `auth.php`.

3. Añadir más páginas si se desea (por ejemplo, un dashboard o un módulo de productos).

---

## Paso 7. Ajustes sugeridos de estudio

- Extender la tabla `clientes` con más campos (por ejemplo, NIT, dirección).
- Añadir validaciones del lado del cliente (HTML5 y jQuery).
- Implementar filtros avanzados en DataTables (por ciudad, por rango de fechas).
- Crear un nuevo módulo (ej. productos) reutilizando el patrón:
  - Modelo, controlador, API y página propia.

---
