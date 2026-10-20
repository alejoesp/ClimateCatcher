# Notas de seguridad

## Conexión a la base de datos

La conexión ya no tiene usuario y contraseña escritos directamente en `conexion.php`.

Ahora toma estos valores desde variables de entorno:

- `DB_HOST`
- `DB_NAME`
- `DB_USER`
- `DB_PASSWORD`

`.env.example` muestra qué datos hacen falta, pero no contiene credenciales reales.

## Credenciales antiguas

En una versión anterior del repositorio hubo datos de conexión escritos en el código. Aunque ya no estén en la versión actual, una contraseña que estuvo publicada debe darse por expuesta y cambiarse en el hosting o servidor correspondiente.

## Pendientes del prototipo

ClimateCatcher conserva varias decisiones del desarrollo original que no usaría igual en una versión de producción. Antes de publicar una nueva versión revisaría, como mínimo:

- guardar las contraseñas con `password_hash()` y validarlas con `password_verify()`;
- separar la contraseña del usuario del código de la estación;
- usar un token propio y revocable para cada dispositivo;
- recibir la telemetría por HTTPS y POST en lugar de enviar la clave en la URL;
- validar rangos razonables para temperatura, humedad, presión y luminosidad;
- validar de forma estricta el código de estación antes de usarlo para construir nombres de tablas;
- evitar devolver mensajes internos de MySQL desde el endpoint;
- sacar los archivos de log del directorio público;
- agregar protección CSRF a las acciones que modifican o eliminan datos;
- endurecer la configuración de sesión y regenerar el identificador después del inicio de sesión;
- escapar cualquier dato almacenado antes de imprimirlo en HTML.

El repositorio sirve para mostrar el funcionamiento del prototipo y su evolución. No lo considero listo para exponer directamente a Internet sin resolver primero estos puntos.
