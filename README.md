# GUÍA PASO A PASO PARA IMPLEMENTAR EL PROYECTO

Esta guía está pensada para que el proyecto pueda ser desplegado en un entorno PHP + PostgreSQL.

## Paso 1. Preparar la base de datos en PostgreSQL

1. Crear la base de datos y la extensión de seguridad:
   ```sql
   CREATE DATABASE BarberShop;
   CREATE EXTENSION IF NOT EXISTS pgcrypto;
   ```

2. Crear las tablas usuarios, clientes, servicios y citas:

   ```sql
   CREATE TABLE usuarios (
      id SERIAL PRIMARY KEY,
      username VARCHAR(50) UNIQUE NOT NULL,
      password_hash TEXT NOT NULL,
      nombre VARCHAR(100) NOT NULL
   );

   CREATE TABLE clientes (
      id SERIAL PRIMARY KEY,
      nombre VARCHAR(100) NOT NULL,
      email VARCHAR(100),
      telefono VARCHAR(50),
      notas TEXT
   );

   CREATE TABLE servicios (
      id SERIAL PRIMARY KEY,
      nombre VARCHAR(100) UNIQUE NOT NULL,
      precio NUMERIC(10, 2) NOT NULL,
      duracion_minutos INTEGER NOT NULL
   );

   CREATE TABLE citas (
      id SERIAL PRIMARY KEY,
      cliente_id INTEGER NOT NULL REFERENCES clientes(id) ON DELETE CASCADE,
      fecha_hora TIMESTAMP WITHOUT TIME ZONE NOT NULL,
      servicio VARCHAR(100) NOT NULL,
      estado VARCHAR(50) NOT NULL DEFAULT 'Confirmada',
      creado_en TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
   );
   ```

3. Crear datos de prueba:

   ## Datos de prueba para USUARIOS
   ```sql
    INSERT INTO usuarios(username, password_hash, nombre)
    VALUES (
        'Sergio',
        crypt('sergio123', gen_salt('bf')),
        'Sergio Ruano'
    );
    ```

    ## Datos de prueba para CLIENTES
    ```sql
    INSERT INTO clientes (nombre, email, telefono, notas) VALUES
    ('Pedro Gomez', 'pedro.gomez@yahoo.com', '3101234567', 'Ninguna.');
    ('Luis Rojas', 'luis.rojas@gmail.com', '3209876543', 'Primera vez. Preguntar estilo deseado.');
    ('Luis Dajome', 'luis.dajomez@hotmail.com', '3001112233', 'Cliente regular. Prefiere tijera, no máquina');
    ```

    ## Datos de prueba para SERVICIOS
    ```sql
    INSERT INTO servicios (nombre, precio, duracion_minutos) VALUES 
    ('Corte de Pelo', 15.00, 30);
    ('Arreglo de Barba', 10.00, 15);
    ('Corte + Barba', 25.00, 60);
    ```

4. Configuración de conexión a PostgreSQL

    ## Ruta: backend/config/database.php
    ```sql
    $host = 'localhost';
    $port = '5432';     -- Puerto por defecto de PostgreSQL. Cámbialo si usas uno diferente.
    $db   = 'BarberShop';
    $user = 'postgres';      -- Nombre de usuario por defecto de PostgreSQL. Cámbialo si usas uno diferente.
    $pass = 'sergio123';  -- Cambia esta contraseña por la contraseña real.
    ```
