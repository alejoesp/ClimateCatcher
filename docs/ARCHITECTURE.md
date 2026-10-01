# Arquitectura técnica — ClimateCatcher

## Visión general

ClimateCatcher se compone de dos grandes partes:

1. **Dispositivo meteorológico**, encargado de capturar variables ambientales.
2. **Aplicación web**, encargada de validar, almacenar, consultar y visualizar las mediciones.

El objetivo del sistema es mantener un flujo completo desde el sensor hasta la visualización final del usuario.

## Componentes

### 1. Ingesta de datos

`datosestacion.php` funciona como endpoint de entrada.

Responsabilidades:
- recibir identificador de estación;
- recibir humedad, temperatura, luminosidad y presión;
- validar tipos de datos;
- comprobar la existencia de la estación;
- delegar la persistencia a `insertardatos.php`;
- devolver una respuesta JSON.

### 2. Persistencia

`insertardatos.php` registra las mediciones utilizando PDO.

La fecha y hora se generan en el servidor con zona horaria de Argentina.

### 3. Autenticación y usuarios

Los archivos principales son:
- `registro.php`
- `login.php`
- `logout.php`
- `login_admin.php`

Las sesiones PHP mantienen el contexto del usuario autenticado y de la estación asociada.

### 4. Dashboard

`dashboard.php` concentra la experiencia de consulta.

Incluye:
- últimos valores registrados;
- mínimos;
- máximos;
- promedios;
- histórico tabular;
- paginación;
- selección de fechas;
- gráficos con Chart.js;
- acceso a reportes PDF.

### 5. Administración

`dashboard_admin.php` permite consultar:
- usuarios registrados;
- estaciones disponibles;
- relación entre usuarios y estaciones.

### 6. Reportes

`generar_reporte.php` utiliza FPDF para construir un documento descargable con datos históricos.

## Flujo lógico

```text
Sensores
   │
   ▼
Dispositivo
   │
   │ HTTP
   ▼
datosestacion.php
   │
   ├── valida estación
   ├── valida mediciones
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

## Base de datos

La implementación utiliza una base relacional con entidades para:
- usuarios;
- estaciones;
- relación usuario-estación;
- mediciones meteorológicas.

La implementación original genera tablas de mediciones asociadas al código de estación.

## Consideraciones de seguridad

La rama de portfolio elimina credenciales directas del código y las reemplaza por variables de entorno.

Para una versión de producción se recomienda además:
- `password_hash()` y `password_verify()`;
- tokens distintos al código físico de estación;
- HTTPS obligatorio;
- POST en vez de GET para la ingesta;
- rate limiting;
- validación de rangos físicos;
- logs externos;
- permisos mínimos para el usuario de MySQL.

## Evolución sugerida

Una evolución técnica natural sería separar la aplicación en capas:

```text
src/
├── Controllers/
├── Services/
├── Repositories/
├── Models/
└── Config/
```

También sería recomendable una API REST dedicada y un frontend desacoplado.
