# SteamWish

SteamWish es una aplicación web que permite a los usuarios buscar juegos en Steam, ver precios y descuentos, y guardar juegos en una lista de deseos personal. Se conecta a las APIs públicas de Steam para obtener datos reales sin requerir una cuenta de desarrollador de Steam.

---

## Qué hace el proyecto

- Muestra un panel en la página de inicio con los juegos más jugados, juegos en tendencia (en oferta) y próximos lanzamientos obtenidos en vivo desde Steam.
- Permite a los usuarios buscar cualquier juego por nombre.
- Muestra una página de detalles completa para cada juego, incluyendo el historial de precios desde IsThereAnyDeal.
- Permite a los usuarios registrados añadir juegos a una lista de deseos personal.
- **Seguimiento de Precios y Notificaciones**: Un sistema en segundo plano revisa diariamente los precios de la Wishlist y notifica en la app si algún juego baja de precio.
- **Diseño Neo-Brutalista y UX**: Toda la interfaz cuenta con un diseño premium Neo-Brutalista, incluyendo una pantalla de carga global animada con GIFs entre transiciones.
- Tiene un formulario de contacto y una página sobre nosotros.
- El inicio de sesión de Steam está implementado usando OpenID (no se requiere contraseña).

---

## Tecnología utilizada

| Área | Tecnología |
|---|---|
| Backend | PHP 8.2, Laravel 12 |
| Frontend | Plantillas Blade, Tailwind CSS, JavaScript Vanilla |
| Base de Datos | MySQL (vía XAMPP) |
| Iconos | Lucide Icons (cargados desde CDN) |
| Fuentes | Space Grotesk, Space Mono (Google Fonts) |
| Herramienta de construcción | Vite |

---

## Cómo ejecutar localmente

Requisitos: PHP 8.2+, Composer, Node.js, MySQL ejecutándose (XAMPP funciona bien)

```bash
# 1. Instalar dependencias de PHP
composer install

# 2. Instalar dependencias de JavaScript
npm install

# 3. Copiar el archivo de entorno
cp .env.example .env

# 4. Generar la clave de la aplicación
php artisan key:generate

# 5. Configurar la base de datos en .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

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
APP_KEY=         # Generada automáticamente
DB_HOST=         # Usualmente 127.0.0.1
DB_DATABASE=     # Nombre de tu base de datos MySQL
DB_USERNAME=     # Usualmente root
DB_PASSWORD=     # Usualmente vacío en XAMPP

STEAM_API_KEY=   # Desde https://steamcommunity.com/dev/apikey
ITAD_API_KEY=    # Desde https://isthereanydeal.com (para el historial de precios)
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
| `/dashboard` | Panel de usuario (requiere login) |
| `/wishlist` | Juegos guardados del usuario (requiere login) |
| `/notificaciones` | Centro de notificaciones de bajadas de precio (requiere login) |

---

## Rutas de la API

Estas rutas devuelven JSON, no páginas HTML. Son llamadas por JavaScript desde el frontend.

| URL | Qué devuelve |
|---|---|
| `/api/search?query=...` | Lista de juegos que coinciden con la búsqueda |
| `/api/home-data` | Juegos más jugados, en tendencia y próximos para la Home |

---

## Controladores

Todos los controladores están en `app/Http/Controllers/`.

**HomeController**
Gestiona las páginas principales: inicio, sobre nosotros, contacto. También tiene el método `homeData()` que obtiene las categorías destacadas de la API de Steam y devuelve JSON para las secciones de la Home.

**GameController**
Gestiona la página de resultados de búsqueda y la página de detalles del juego. La página de detalles obtiene la información completa de Steam, incluyendo capturas de pantalla, descripción, editor y desarrollador. También integra el historial de precios.

**SearchController**
Gestiona el endpoint JSON de búsqueda utilizado por la barra de búsqueda y la página de resultados.

**AuthController**
Gestiona el login con Steam OpenID. El usuario es redirigido a Steam, Steam lo devuelve con una identidad verificada, y el controlador crea o busca al usuario en la base de datos.

**WishlistController**
Dos acciones: mostrar la página de la lista de deseos y alternar un juego en la lista (añadir o eliminar). También guarda el precio base del juego cuando se añade por primera vez.

**NotificationController**
Gestiona el sistema de notificaciones en la app: vista previa en el menú superior, página completa y opciones para marcar alertas como leídas.

---

## Tareas en Segundo Plano (Jobs)

**CheckWishlistPrices** (`app/Jobs/CheckWishlistPrices.php`)
Compara periódicamente el precio actual de los juegos en las listas de deseos de los usuarios con el precio base al que lo guardaron. Si detecta una bajada, genera una alerta en el sistema de notificaciones. Se ejecuta automáticamente mediante el Programador de Laravel (`routes/console.php`).

---

## Servicios y APIs Externas

La lógica de negocio y los wrappers se encuentran en `app/Services/` y `app/Includes/`.

**GameService** (`app/Services/GameService.php`)
Proporciona caché para los detalles de los juegos para minimizar las peticiones a APIs externas.

**Steam API Wrapper** (`app/Includes/steam_wrapper.php`)
Contiene funciones PHP puras para obtener datos directamente de Steam, como `getAppDetails()` y `getSearch()`.

**IsThereAnyDeal Wrapper** (`app/Includes/isthereanydeal_wrapper.php`)
Proporciona `getPriceHistory()` para obtener datos históricos de precios de un juego, mostrados en la página de detalles.

---

## Cómo funciona la búsqueda

1. El usuario escribe en la barra de búsqueda.
2. JavaScript llama a `/api/search?query=...` o el usuario envía una petición GET estándar a `/search`.
3. SearchController llama a `getSearch()` desde el wrapper de Steam.
4. Para los 10 primeros resultados, obtiene información detallada usando `GameService` (que utiliza caché para optimizar la velocidad).
5. Cruza los datos para ver si los juegos obtenidos están en la lista de deseos del usuario logueado.
6. Los resultados se devuelven como JSON o se renderizan mediante la vista Blade.

---

## Cómo funcionan los datos de la Home

1. La página carga instantáneamente mostrando cajas de carga animadas (skeleton loaders).
2. JavaScript llama a `/api/home-data`.
3. Laravel llama a `https://store.steampowered.com/api/featuredcategories`.
4. Esta URL pública de Steam devuelve los más vendidos, ofertas especiales y próximos juegos.
5. Laravel limpia los datos y devuelve solo lo que el frontend necesita.
6. JavaScript reemplaza las cajas de carga con las tarjetas de juego reales.

---

## Componentes Blade

Piezas de UI reutilizables en `resources/views/components/`:

| Componente | Dónde se usa |
|---|---|
| `navbar` | Todas las páginas (en el layout) |
| `footer` | Todas las páginas (en el layout) |
| `button` | Varias páginas para botones estilizados |
| `game-card` | Resultados de búsqueda, listados de juegos |
| `game-list-item` | Sección de más jugados de la Home |
| `trending-item` | Sección de tendencia de la Home |
| `upcoming-card` | Sección de próximos de la Home |
| `section-title` | Cabeceras de sección con etiquetas |

---

## Resumen de la estructura de carpetas

```
app/
  Http/Controllers/   - Todos los controladores
  Models/             - Game, User, Wishlist
  Services/           - GameService
  Includes/           - Wrappers de APIs externas (steam_wrapper.php, etc.)

database/
  migrations/         - Definiciones de tablas de la base de datos

resources/
  views/
    layouts/          - Layout base HTML (app.blade.php)
    components/       - Piezas de UI reutilizables
    pages/            - Un archivo por página

routes/
  web.php             - Todas las rutas URL
```

---

## Qué no está implementado todavía

- Caching con Redis para resultados de búsqueda.
- Historial de precios almacenado internamente a largo plazo (por ahora depende de la API en vivo de ITAD).
- Página de perfil de usuario completa y pública.
- La página del panel (dashboard) aún necesita gráficas de estadísticas de usuario reales.
