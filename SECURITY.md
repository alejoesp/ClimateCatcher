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

## Pendientes del proyecto original

Si retomara ClimateCatcher para usarlo fuera de un prototipo, revisaría principalmente estos puntos:

- guardar contraseñas con `password_hash()` y validarlas con `password_verify()`;
- no usar el mismo dato como código de estación y contraseña;
- pasar el envío de mediciones a POST;
- trabajar siempre sobre HTTPS;
- usar un token distinto para cada dispositivo;
- validar límites razonables para cada sensor;
- no mostrar errores internos de MySQL al usuario;
- mantener logs fuera del directorio público.
