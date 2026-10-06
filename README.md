# Tingo María Turismo

Guía informativa de lugares, comida y fiestas de Tingo María. PHP + MySQL/MariaDB, sin frameworks.

## Requisitos

- PHP 8.0 o superior con la extensión `pdo_mysql`
- MySQL o MariaDB

## Instalación en local

1. Importa la base de datos (crea la BD `turismo_tingo`, las tablas y los datos de ejemplo):

   ```bash
   mysql -u root -p < database.sql
   ```

2. Crea el usuario de base de datos que usa la aplicación:

   ```sql
   CREATE USER 'turismo_user'@'localhost' IDENTIFIED BY 'CambiaEstaClave123';
   GRANT ALL PRIVILEGES ON turismo_tingo.* TO 'turismo_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

3. Si usaste otro usuario o contraseña, cámbialos en `config.php`.

4. Levanta la aplicación:

   - Con XAMPP/Laragon: copia la carpeta a `htdocs` (o `www`) y abre `http://localhost/turismo-tingo/`.
   - Con el servidor de PHP: dentro de la carpeta ejecuta `php -S localhost:8080` y abre `http://localhost:8080/`.

## Panel de administración

- Dirección: `admin/login.php`
- Usuario: `admin`
- Contraseña: `Tingo2026!`

Para cambiar la contraseña, genera un hash nuevo y actualiza la tabla:

```bash
php -r 'echo password_hash("TuNuevaClave", PASSWORD_BCRYPT), "\n";'
```

```sql
UPDATE turismo_tingo.usuarios SET clave_hash = 'PEGA_AQUI_EL_HASH' WHERE usuario = 'admin';
```

## Estructura

```
config.php            Datos de conexión a la BD (lo único que cambia al migrar)
database.sql          Esquema y datos de ejemplo
index.php             Lista de lugares con filtro por categoría y buscador
lugar.php             Ficha de un lugar
admin/                Login y CRUD de lugares
includes/             Conexión PDO, funciones, cabecera y pie
assets/css/           Estilos
```

## Tablas

- `categorias`: id, nombre
- `lugares`: id, categoria_id (FK), nombre, resumen, descripcion, ubicacion, recomendaciones, imagen_url, destacado, creado_en
- `usuarios`: id, usuario, nombre, clave_hash
