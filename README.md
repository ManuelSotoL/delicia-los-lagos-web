# Delicias los Lagos Web

Proyecto PHP para pedidos de tortas compatible con hosting compartido y cPanel.

## Requisitos
- PHP 8.x
- MySQL/MariaDB
- Apache
- HTTPS/SSL

## Instalación en cPanel
1. Subir la carpeta completa al directorio public_html.
2. Crear una base de datos MySQL desde cPanel.
3. Importar el archivo database/schema.sql.
4. Copiar `config/database.example.php` como `config/database.php` y configurar la conexión.
5. Ajustar la URL y datos del negocio en config/config.php.
6. Crear un hash de contraseña seguro con `php -r "echo password_hash('TU_CONTRASEÑA_SEGURA', PASSWORD_DEFAULT), PHP_EOL;"`.
7. Insertar la cuenta de administración usando ese hash:

   ```sql
   INSERT INTO admin_users (username, password_hash, full_name, is_active)
   VALUES ('admin', 'PEGA_AQUI_EL_HASH_GENERADO', 'Administrador', 1);
   ```

8. Acceder a `/admin/login.php`.

## Seguridad
- PDO con prepared statements.
- Password hashing con password_hash().
- Sesiones seguras.
- CSRF en formularios administrativos.
- Sanitización HTML con htmlspecialchars().

## Personalización
- El número de WhatsApp debe configurarse solo en `config/config.php`.
- Los horarios de entrega y días mínimos de anticipación se gestionan desde /admin/configuracion.php.
