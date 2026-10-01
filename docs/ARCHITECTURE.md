# Cómo funciona ClimateCatcher

Este archivo explica la parte interna del proyecto y qué hace cada pieza.

## Recorrido de los datos

El flujo principal es este:

```text
Estación
   │
   │ envía temperatura, humedad,
   │ luminosidad y presión
   ▼
datosestacion.php
   │
   ├── comprueba la estación
   ├── valida los valores recibidos
   ▼
insertardatos.php
   │
   ▼
MySQL
   │
   ├───────────────┐
   ▼               ▼
dashboard.php   dashboard_admin.php
   │
   ├── Chart.js
   └── FPDF
```

La estación manda las mediciones al servidor. `datosestacion.php` recibe esos valores, revisa el código de la estación y, si todo está bien, llama a `insertardatos.php` para guardarlos.

## Archivos que manejan los datos

### `datosestacion.php`

Es el punto de entrada de las mediciones.

Recibe:
- código de estación;
- humedad;
- temperatura;
- luminosidad;
- presión.

Después busca la estación en la base de datos y devuelve una respuesta JSON según el resultado.

### `insertardatos.php`

Hace el INSERT de las mediciones y agrega la fecha y hora del servidor.

### `conexion.php`

Centraliza la conexión con MySQL. La conexión usa variables de entorno para no dejar usuario y contraseña dentro del repositorio.

## Usuarios y sesiones

Los archivos principales de esta parte son:

- `registro.php`
- `login.php`
- `logout.php`
- `login_admin.php`

El acceso del usuario se mantiene con sesiones PHP. Al iniciar sesión también se conserva la estación asociada para saber qué datos mostrar en el dashboard.

## Dashboard

`dashboard.php` muestra la información de la estación.

Ahí se calculan y muestran:
- último valor registrado;
- mínimos y máximos;
- promedios;
- historial;
- paginación;
- búsqueda por fecha;
- gráficos con Chart.js.

También desde esa pantalla se puede generar un reporte PDF.

## Administración

`dashboard_admin.php` es una vista separada para consultar usuarios y estaciones registradas, además de ver qué estación está asociada a cada usuario.

## Reportes

`generar_reporte.php` usa FPDF para armar un PDF con los registros guardados.

## Base de datos

El proyecto trabaja con tablas para usuarios, estaciones y relaciones entre ambos. Las mediciones se guardan en tablas asociadas al código de cada estación, que es la forma en que estaba resuelto el prototipo original.

## Cosas a tener en cuenta

Hay partes que hoy haría de otra manera si retomara el proyecto, especialmente:
- separar el código de estación de la contraseña del usuario;
- usar hashes para las contraseñas;
- enviar la telemetría por POST y HTTPS;
- validar mejor los rangos de los sensores;
- sacar por completo los logs del directorio público.

No las cambié en esta versión porque forman parte de cómo estaba construido el proyecto original.
