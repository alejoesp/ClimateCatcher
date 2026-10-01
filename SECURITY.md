# Seguridad

## Credenciales

Las credenciales de base de datos no deben almacenarse directamente en el repositorio.

La rama de portfolio utiliza las siguientes variables de entorno:

- `DB_HOST`
- `DB_NAME`
- `DB_USER`
- `DB_PASSWORD`

El archivo `.env.example` funciona únicamente como referencia y no contiene credenciales reales.

## Importante sobre credenciales históricas

Este repositorio tuvo credenciales incorporadas directamente al código en una versión anterior. Aunque se eliminen del archivo actual, cualquier credencial publicada previamente debe considerarse comprometida y debe ser reemplazada en el proveedor correspondiente.

## Recomendaciones adicionales

Para una versión productiva del proyecto se recomienda:

- usar `password_hash()` y `password_verify()` para contraseñas;
- separar el código físico de estación de la contraseña del usuario;
- utilizar HTTPS;
- usar POST para el envío de telemetría;
- implementar tokens de dispositivo revocables;
- limitar la frecuencia de solicitudes;
- validar rangos aceptables de sensores;
- evitar exponer mensajes internos de base de datos;
- mantener logs y archivos de entorno fuera del repositorio.
