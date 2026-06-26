# Plan: Web de Centros de Acopio - Terremoto Venezuela

## Stack Tecnológico

| Componente | Tecnología |
|---|---|
| Backend | PHP 8+ |
| Base de datos | MySQL |
| Frontend | HTML + Bootstrap 5 (CDN) + JS vanilla |
| Fotos | Almacenamiento local en /uploads/ |
| CAPTCHA | Cloudflare Turnstile (invisible, liviano) |
| Mapas | ❌ No incluido (solo texto) |

---

## Estructura del Proyecto

```
acopio/
├── index.php              # Lista de centros con filtros
├── registrar-centro-acopio.php  # Paso 1: Seleccionar Estado/Municipio + buscar existentes
├── crear-paso2.php        # Paso 2: Formulario completo del centro
├── centro.php             # Detalle de centro + tabla inventario
├── api/
│   ├── centros.php        # CRUD centros
│   ├── inventario.php     # CRUD inventario (falta/sobra) con soft delete
│   ├── buscar-centros.php # Búsqueda de centros por estado/municipio
│   ├── upload.php         # Subida de fotos a /uploads/
│   └── helper.php         # Funciones compartidas (hash, JSON, rate limit, Turnstile)
├── config/
│   └── database.php       # Conexión MySQL con PDO
├── sql/
│   └── schema.sql         # Esquema de BD + inserts de Venezuela
└── assets/
    └── js/
        └── app.js         # JS global (filtros dinámicos, Turnstile)
```

---

## Base de Datos (MySQL)

```sql
CREATE TABLE estados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE municipios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estado_id INT NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    FOREIGN KEY (estado_id) REFERENCES estados(id)
);

CREATE TABLE parroquias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    municipio_id INT NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    FOREIGN KEY (municipio_id) REFERENCES municipios(id)
);

CREATE TABLE centros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estado_id INT NOT NULL,
    municipio_id INT NOT NULL,
    parroquia_id INT,
    direccion TEXT NOT NULL,
    direccion_hash CHAR(40) UNIQUE NOT NULL,
    foto_url VARCHAR(500),
    telefono VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (estado_id) REFERENCES estados(id),
    FOREIGN KEY (municipio_id) REFERENCES municipios(id),
    FOREIGN KEY (parroquia_id) REFERENCES parroquias(id)
);

CREATE TABLE inventario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    centro_id INT NOT NULL,
    item VARCHAR(200) NOT NULL,
    tipo ENUM('falta', 'sobra') NOT NULL,
    cantidad VARCHAR(100) NOT NULL DEFAULT '',
    activo TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (centro_id) REFERENCES centros(id) ON DELETE CASCADE
);
```

**Hash de dirección:** Se genera `SHA1(estado_id + '_' + municipio_id + '_' + direccion_normalizada)` donde la dirección se normaliza quitando tildes, eñes, espacios múltiples y pasando a minúsculas.

---

## Flujo de la Aplicación

### 1. Página Principal (`index.php`)
- Filtros por Estado y Municipio (dropdowns cargados desde BD)
- Renderizado SSR (PHP genera HTML directamente, sin fetch JS extra)
- Paginación: 20 centros por página
- Tarjetas de centros con:
  - Estado, Municipio, Dirección (truncada)
  - Teléfono
  - Indicador visual: "❌ Falta: ..." / "✅ Sobra: ..."
  - Enlace a "Ver detalle"
- Botón "Registrar nuevo centro de acopio"

### 2. Crear Centro - Paso 1 (`registrar-centro-acopio.php`)
- Seleccionar **Estado** (dropdown)
- Seleccionar **Municipio** (dropdown, se filtra por estado vía JS)
- Mostrar lista de centros **ya existentes** en ese municipio
- Si el usuario ve el suyo, hace clic y va al detalle para editar inventario
- Si no ve el suyo, botón "No está mi centro, continuar" → va al Paso 2

### 3. Crear Centro - Paso 2 (`crear-paso2.php`)
- Formulario con:
  - Estado, Municipio, Parroquia (opcional)
  - **Dirección exacta** (textarea)
  - **Foto** (subida a R2)
  - **Teléfono de recepción**
- Turnstile (invisible, sin interrupción)
- Validación: al enviar se normaliza dirección, se genera hash y se verifica UNIQUE en BD
- Si hay duplicado: muestra el centro existente
- Si es nuevo: se guarda y redirige al detalle

### 4. Detalle de Centro (`centro.php?id=X`)
- Toda la información del centro
- Foto (si tiene)
- **Tabla de inventario** dividida en 2 columnas:

  | 🟢 Lo que falta | 🔵 Lo que sobra |
  |---|---|
  | Agua (100 botellas) | Ropa (50 piezas) |
  | Medicinas | Colchonetas (20) |

- Botón **"Agregar ítem"** → formulario inline con Turnstile
  - Falta / Sobra
  - Nombre del artículo
  - Cantidad (texto libre, ej: "100 botellas")
- Botón **"Eliminar"** → soft delete (activo = 0) con confirmación + Turnstile

### 5. API REST

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/api/centros.php` | Listar centros (paginado, con filtros) |
| GET | `/api/centros.php?id=X` | Detalle de centro |
| POST | `/api/centros.php` | Crear centro (Turnstile) |
| GET | `/api/buscar-centros.php?estado=X&municipio=Y` | Buscar existentes |
| GET | `/api/inventario.php?centro_id=X` | Listar ítems activos |
| POST | `/api/inventario.php` | Agregar ítem (Turnstile) |
| DELETE | `/api/inventario.php?id=X` | Soft delete ítem (Turnstile) |
| POST | `/api/upload.php` | Subir foto a R2 |

---

## Prevención de Duplicados

1. **Paso 1 obligatorio:** Buscar por Estado + Municipio antes de crear
2. **Hash SHA1:** `direccion_hash` = `SHA1(estado_id + '_' + municipio_id + '_' + direccion_normalizada)`
3. **Índice UNIQUE** en `direccion_hash` — rechazo a nivel BD, sin falsos positivos por tildes/espacios

---

## Fotos

- Las imágenes se guardan en `/uploads/` en el servidor
- En `centros.foto_url` se almacena la ruta relativa (ej: `/uploads/centro_1234.jpg`)
- Tamaño máximo: 5 MB
- Formatos: jpg, png, webp

---

## Control de Spam

| Medida | Descripción |
|---|---|
| Cloudflare Turnstile | Invisible, sin fricción, funciona en conexiones lentas |
| Rate limiting | Máximo 10 acciones por IP por hora |
| Campo honeypot | Campo oculto en formularios |
| Soft delete | Inventario usa `activo=0`, no DELETE real |

---

## Optimizaciones para Venezuela

| Aspecto | Solución |
|---|---|
| **Conexiones lentas (2G/3G)** | SSR con PHP, sin fetch JS en carga inicial |
| **Peso de página** | Gzip/Deflate en servidor + Bootstrap CDN con compresión |
| **Paginación** | 20 centros por página para evitar listados enormes |
| **Municipios** | Nivel Estado → Municipio (la parroquia es opcional) |
| **Colación UTF-8** | `utf8mb4_unicode_ci` en toda la BD |

---

## Orden de Implementación

1. `sql/schema.sql` — Tablas + inserts de estados/municipios/parroquias de Venezuela
2. `config/database.php` — Conexión PDO
3. `api/helper.php` — Funciones compartidas (hash, Turnstile, rate limit, JSON response)
4. `api/centros.php` — CRUD con paginación y filtros
5. `api/inventario.php` — CRUD con soft delete
6. `api/buscar-centros.php` — Búsqueda para paso 1
7. `index.php` — Lista con filtros SSR
8. `registrar-centro-acopio.php` — Paso 1 con búsqueda
9. `crear-paso2.php` — Formulario + Turnstile + validación hash
10. `centro.php` — Detalle + tabla inventario editable
11. `api/upload.php` — Subida de fotos a /uploads/

---

## Refugios (Ampliación)

Se añade una nueva entidad **refugios** (albergues para personas desplazadas) con la misma lógica base que los centros de acopio, pero con tablas separadas para mantener la independencia de datos.

### Estructura Nuevas Tablas

```sql
CREATE TABLE refugios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estado_id INT NOT NULL,
    municipio_id INT NOT NULL,
    parroquia_id INT NULL,
    direccion TEXT NOT NULL,
    direccion_hash CHAR(40) UNIQUE NOT NULL,
    foto_url VARCHAR(500),
    telefono VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (estado_id) REFERENCES estados(id),
    FOREIGN KEY (municipio_id) REFERENCES municipios(id),
    FOREIGN KEY (parroquia_id) REFERENCES parroquias(id)
);

CREATE TABLE inventario_refugios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    refugio_id INT NOT NULL,
    item VARCHAR(200) NOT NULL,
    tipo ENUM('falta', 'sobra') NOT NULL,
    cantidad VARCHAR(100) NOT NULL DEFAULT '',
    activo TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (refugio_id) REFERENCES refugios(id) ON DELETE CASCADE,
    KEY idx_inventario_refugio (refugio_id, activo)
);
```

La tabla `reportes` se modifica para que `centro_id` sea nullable y se agrega `refugio_id` nullable, permitiendo que tanto centros como refugios compartan el mismo sistema de reportes comunitarios.

### Archivos Nuevos

| Archivo | Propósito |
|---|---|
| `api/refugios.php` | CRUD refugios (GET listar/detalle, POST crear) |
| `api/inventario-refugios.php` | CRUD inventario de refugios (GET, POST, DELETE soft) |
| `api/buscar-refugios.php` | Búsqueda de refugios por estado/municipio |
| `views/refugios.php` | Listado de refugios con filtros y paginación |
| `views/registrar-refugio.php` | Formulario de registro de refugio |
| `views/refugio.php` | Detalle del refugio + inventario + reportes |

### Archivos Modificados

| Archivo | Cambio |
|---|---|
| `sql/schema.sql` | Nuevas tablas + modificar `reportes` |
| `router.php` | Rutas amigables para refugios |
| `.htaccess` | Rewrite rules para refugios |
| `index.php` | Navegación a la sección refugios |
| `api/reportes.php` | Soporte para `refugio_id` |
| `assets/js/app.js` | Funciones JS para formularios de refugios |

### API - Refugios

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/api/refugios.php` | Listar refugios (paginado, con filtros) |
| GET | `/api/refugios.php?id=X` | Detalle de refugio |
| POST | `/api/refugios.php` | Crear refugio (Turnstile) |
| GET | `/api/buscar-refugios.php?estado_id=X&municipio_id=Y` | Buscar existentes |
| GET | `/api/inventario-refugios.php?refugio_id=X` | Listar ítems activos |
| POST | `/api/inventario-refugios.php` | Agregar ítem (Turnstile) |
| DELETE | `/api/inventario-refugios.php?id=X` | Soft delete ítem (Turnstile) |

### Rutas Amigables

| Ruta | Archivo |
|---|---|
| `/refugios` | `views/refugios.php` |
| `/registrar-refugio` | `views/registrar-refugio.php` |
| `/refugio/{id}` | `views/refugio.php?id=X` |

### Flujo de Reportes Compartidos

La tabla `reportes` ahora acepta `centro_id` o `refugio_id` (uno de los dos obligatorio):

- Si `centro_id` está presente → reporte asociado a un centro de acopio
- Si `refugio_id` está presente → reporte asociado a un refugio
- La tabla `reportes_denuncias` se mantiene igual (FK a `reportes.id`)
