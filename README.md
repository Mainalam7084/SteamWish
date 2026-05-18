# SteamWish

SteamWish es una aplicación web que permite a los usuarios buscar juegos en Steam, ver precios y descuentos, y guardar juegos en una lista de deseos personal. Se conecta a las APIs públicas de Steam e IsThereAnyDeal para obtener datos reales de precios e historial.

---

## Qué hace el proyecto

- Muestra un panel en la página de inicio con los juegos más jugados, juegos en tendencia (en oferta) y próximos lanzamientos obtenidos en vivo desde Steam.
- Permite a los usuarios buscar cualquier juego por nombre.
- Muestra una página de detalles completa para cada juego, incluyendo historial de precios desde IsThereAnyDeal y el precio mínimo histórico.
- Permite a los usuarios registrados añadir y gestionar una lista de deseos personal.
- **Seguimiento de precios**: Un job en segundo plano revisa periódicamente los precios de los juegos en la wishlist y genera notificaciones si alguno baja de precio.
- **Notificaciones en la app**: El usuario recibe alertas cuando un juego de su wishlist está en oferta, con información del descuento y precios anterior y actual.
- **Diseño Neo-Brutalista**: Interfaz con animaciones de carga, skeleton loaders y transiciones entre páginas.
- **Personalización**: El usuario puede guardar un color de tema preferido en su perfil.
- Formulario de contacto y página sobre nosotros.
- Login con Steam via OpenID (sin contraseña).

---

## Tecnología utilizada

| Área | Tecnología |
|---|---|
| Backend | PHP 8.2, Laravel 12 |
| Frontend | Plantillas Blade, Tailwind CSS 4, JavaScript Vanilla |
| Base de datos | MySQL |
| Build tool | Vite 7 |
| Iconos | Lucide Icons (CDN) |
| Fuentes | Space Grotesk, Space Mono (Google Fonts) |
| Testing | Pest 3 |

---

## Instalación rápida (un solo comando)

```bash
composer run setup
```

Este comando ejecuta automáticamente: `composer install`, copia `.env.example` a `.env`, genera la clave de la aplicación, ejecuta las migraciones, `npm install` y compila los assets.

Después configura las variables de entorno requeridas en `.env` (ver sección siguiente) y arranca el servidor:

```bash
composer run dev
```

### Instalación manual paso a paso

Requisitos: PHP 8.2+, Composer, Node.js, MySQL ejecutándose.

```bash
# 1. Instalar dependencias de PHP
composer install

# 2. Instalar dependencias de JavaScript
npm install

# 3. Copiar el archivo de entorno
cp .env.example .env

# 4. Generar la clave de la aplicación
php artisan key:generate

# 5. Configurar la base de datos en .env (ver sección de variables)

# 6. Ejecutar las migraciones
php artisan migrate

# 7. Iniciar todo (servidor + cola + vite)
composer run dev
```

La aplicación se ejecuta en http://localhost:8000

---

## Variables de entorno requeridas

Añade estas a tu archivo `.env`:

```
APP_KEY=              # Generada automáticamente con key:generate

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=steamwish
DB_USERNAME=          # Tu usuario de PostgreSQL
DB_PASSWORD=          # Tu contraseña de PostgreSQL

API_KEY=              # Steam Web API key — https://steamcommunity.com/dev/apikey
ITAD_API_KEY=         # IsThereAnyDeal API key — https://isthereanydeal.com
```

---

## Páginas

| URL | Qué muestra |
|---|---|
| `/` | Panel de inicio con juegos más jugados, en tendencia y próximos |
| `/search?q=...` | Página de resultados de búsqueda |
| `/game?appid=...` | Página de detalles completa para un juego |
| `/contact` | Formulario de contacto |
| `/about` | Página sobre nosotros |
| `/login` | Página de login (botón de login con Steam) |
| `/dashboard` | Panel de usuario con estadísticas (requiere login) |
| `/wishlist` | Juegos guardados del usuario (requiere login) |
| `/notificaciones` | Centro de notificaciones de bajadas de precio (requiere login) |

---

## Rutas de la API

Estas rutas devuelven JSON. Son llamadas por JavaScript desde el frontend.

| URL | Auth | Qué devuelve |
|---|---|---|
| `/api/search?query=...` | No | Lista de juegos que coinciden con la búsqueda |
| `/api/home-data` | No | Juegos más jugados, en tendencia y próximos para la Home |
| `/api/wishlist-ids` | No | Array de appids en la wishlist del usuario activo |
| `/api/wishlist-preview` | No | Últimos 3 juegos de la wishlist (para el menú) |
| `/api/notifications-preview` | Sí | Últimas 5 notificaciones (para el dropdown del navbar) |
| `/api/notifications/mark-read/{id}` | Sí | Marca una notificación como leída |
| `/api/notifications/mark-all-read` | Sí | Marca todas las notificaciones como leídas |
| `/api/user/preferences` | Sí | Guarda preferencias del usuario (color de tema) |
| `/wishlist/toggle` | Sí | Añade o elimina un juego de la wishlist |

---

## Controladores

Todos los controladores están en `app/Http/Controllers/`.

**HomeController**
Gestiona las páginas principales: inicio, sobre nosotros, contacto y dashboard. El método `homeData()` obtiene las categorías destacadas de la API de Steam (más vendidos, ofertas, próximos) y devuelve JSON para las secciones de la Home. `savePreferences()` persiste el color de tema del usuario.

**GameController**
Gestiona la página de resultados de búsqueda y la página de detalles del juego. Integra datos de Steam (capturas, descripción, precio actual) con el historial de precios de IsThereAnyDeal.

**SearchController**
Gestiona el endpoint JSON de búsqueda. Llama al wrapper de Steam, obtiene detalles de los 10 primeros resultados via `GameService` (con caché) y cruza datos con la wishlist del usuario.

**AuthController**
Gestiona el login con Steam OpenID: redirige al usuario a Steam, procesa el callback, y crea o actualiza el registro de usuario en la base de datos.

**WishlistController**
Muestra la lista de deseos completa y gestiona el toggle (añadir/eliminar). Al añadir un juego, guarda el precio actual como precio base de referencia y genera una notificación si el descuento es mayor al 50%.

**NotificationController**
Gestiona el sistema de notificaciones: vista previa en el navbar (últimas 5), página completa, y marcar alertas como leídas.

---

## Modelos

Todos los modelos están en `app/Models/`.

| Modelo | Tabla | Descripción |
|---|---|---|
| `User` | `users` | Usuario autenticado via Steam. Almacena `steam_id`, `username`, `avatar`, `preferences` (JSON). |
| `Game` | `games` | Juego de Steam. Clave primaria: `appid`. Almacena nombre, imagen, precio actual, precio base y descuento. |
| `Wishlist` | `wishlists` | Relación usuario-juego. Restricción única `(user_id, appid)`. |
| `PriceNotification` | `price_notifications` | Alerta de bajada de precio. Almacena precio anterior, precio nuevo, descuento y estado de lectura. |

---

## Tareas en Segundo Plano

**Job: CheckWishlistPrices** (`app/Jobs/CheckWishlistPrices.php`)
Compara el precio actual de cada juego en wishlists activas con el precio guardado en la BD. Si detecta una bajada, genera una `PriceNotification` para cada usuario que tenga ese juego. Evita duplicados comprobando si ya existe una notificación no leída con el mismo precio en las últimas 24h.

**Comandos Artisan:**
- `php artisan check:prices` — Despacha el job `CheckWishlistPrices`.
- `php artisan check:wishlist-discounts` — Revisa juegos con descuento mayor al umbral configurado (por defecto 50%).

El job se puede programar en `routes/console.php` para ejecutarse diariamente con el scheduler de Laravel:

```bash
php artisan schedule:run
```

---

## Servicios y APIs Externas

**GameService** (`app/Services/GameService.php`)
Caché de 1 hora para los detalles de los juegos, minimizando peticiones a la API de Steam.

**Steam API Wrapper** (`app/Includes/steam_wrapper.php`)
Funciones PHP puras para comunicarse con Steam: `getAppDetails()`, `getSearch()`, `getAppList()`.

**IsThereAnyDeal Wrapper** (`app/Includes/isthereanydeal_wrapper.php`)
Funciones para historial de precios: `getPriceHistory()`, `getLowestPrice()`, `lookupById()`.

---

## Cómo funciona la búsqueda

1. El usuario escribe en la barra de búsqueda.
2. JavaScript llama a `/api/search?query=...` (o GET estándar a `/search`).
3. `SearchController` llama a `getSearch()` del wrapper de Steam.
4. Para los 10 primeros resultados, obtiene detalles completos via `GameService` (con caché).
5. Cruza los datos con la wishlist del usuario activo para indicar qué juegos ya están guardados.
6. Devuelve JSON al frontend o renderiza la vista Blade.

---

## Cómo funcionan los datos de la Home

1. La página carga instantáneamente mostrando skeleton loaders animados.
2. JavaScript llama a `/api/home-data`.
3. Laravel consulta `featuredcategories` de la API pública de Steam.
4. Se normaliza la respuesta y se devuelven solo los campos necesarios.
5. JavaScript reemplaza los loaders con las tarjetas de juego reales.

---

## Componentes Blade

Piezas de UI reutilizables en `resources/views/components/`:

| Componente | Dónde se usa |
|---|---|
| `navbar` | Todas las páginas (en el layout) |
| `footer` | Todas las páginas (en el layout) |
| `button` | Botones estilizados en varias páginas |
| `game-card` | Resultados de búsqueda, wishlist |
| `game-list-item` | Sección de más jugados en la Home |
| `trending-item` | Sección de tendencia en la Home |
| `upcoming-card` | Sección de próximos en la Home |
| `section-title` | Cabeceras de sección con etiquetas |
| `home-loader` | Skeleton loader animado mientras cargan datos |

---

## Estructura de carpetas

```
app/
  Console/Commands/   - Comandos Artisan (CheckPrices, CheckWishlistDiscounts)
  Http/Controllers/   - AuthController, GameController, HomeController,
                        NotificationController, SearchController, WishlistController
  Includes/           - Wrappers de APIs externas (steam_wrapper.php, isthereanydeal_wrapper.php)
  Jobs/               - CheckWishlistPrices (job de cola)
  Models/             - Game, PriceNotification, User, Wishlist
  Services/           - GameService (caché de detalles de juegos)

database/
  migrations/         - Definiciones de tablas

resources/
  views/
    components/       - Piezas de UI reutilizables
    errors/           - Páginas de error (403, 404, 500...)
    layouts/          - Layout base HTML (app.blade.php)
    pages/            - Un archivo por página

routes/
  web.php             - Todas las rutas URL y API internas
  console.php         - Programación de tareas (scheduler)
```
