# SteamWish

SteamWish is a web application that lets users search for games on Steam, view prices and discounts, and save games to a personal wishlist. It connects to Steam's public APIs to get real data without requiring a Steam account from the developer.

---

## What the project does

- Shows a homepage dashboard with the most played games, trending games (on sale), and upcoming releases fetched live from Steam
- Lets users search for any game by name
- Shows a full detail page for each game including price history from IsThereAnyDeal
- Lets logged-in users add games to a personal wishlist
- Has a contact form and an about page
- Steam login is implemented using OpenID (no password required)

---

## Technology used

| Area | Technology |
|---|---|
| Backend | PHP 8.2, Laravel 12 |
| Frontend | Blade templates, Tailwind CSS, Vanilla JavaScript |
| Database | MySQL (via XAMPP) |
| Icons | Lucide Icons (loaded from CDN) |
| Fonts | Space Grotesk, Space Mono (Google Fonts) |
| Build tool | Vite |
| Testing | Pest (PHP testing framework) |

---

## How to run locally

Requirements: PHP 8.2+, Composer, Node.js, MySQL running (XAMPP works fine)

```bash
# 1. Install PHP dependencies
composer install

# 2. Install JavaScript dependencies
npm install

# 3. Copy the environment file
cp .env.example .env

# 4. Generate the application key
php artisan key:generate

# 5. Set up the database in .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 6. Run the migrations
php artisan migrate

# 7. Start everything (server + queue + vite)
composer run dev
```

The application runs at http://localhost:8000

---

## Environment variables required

Add these to your `.env` file:

```
APP_KEY=         # Generated automatically
DB_HOST=         # Usually 127.0.0.1
DB_DATABASE=     # Your MySQL database name
DB_USERNAME=     # Usually root
DB_PASSWORD=     # Usually empty in XAMPP

STEAM_API_KEY=   # From https://steamcommunity.com/dev/apikey
ITAD_API_KEY=    # From https://isthereanydeal.com (for price history)
```

---

## Pages

| URL | What it shows |
|---|---|
| `/` | Homepage dashboard with most played, trending and upcoming games |
| `/search?q=...` | Search results page |
| `/game?appid=...` | Full detail page for one game |
| `/contact` | Contact form |
| `/about` | About page |
| `/login` | Login page (Steam login button) |
| `/dashboard` | User dashboard (requires login) |
| `/wishlist` | User's saved games (requires login) |

---

## API routes

These routes return JSON, not HTML pages. They are called by JavaScript from the frontend.

| URL | What it returns |
|---|---|
| `/api/search?query=...` | List of games matching the search query |
| `/api/home-data` | Most played, trending and upcoming games for the homepage |

---

## Controllers

All controllers are in `app/Http/Controllers/`.

**HomeController**
Handles all the main pages: home, about, contact. Also has the `homeData()` method that fetches from Steam's featured categories API and returns JSON for the homepage sections.

**GameController**
Handles the search results page and the game detail page. The detail page fetches full game information from Steam including screenshots, description, publisher and developer.

**SearchController**
Handles the JSON search endpoint used by the search bar. Delegates all logic to SearchService.

**AuthController**
Handles Steam OpenID login. The user gets redirected to Steam, Steam sends them back with a verified identity, and the controller creates or finds the user in the database.

**WishlistController**
Two actions: show the wishlist page, and toggle a game in the wishlist (add or remove).

---

## Services

All service classes are in `app/Services/`.

**SteamService**
Wraps all calls to the Steam API. Uses Laravel's Http client instead of raw PHP functions. Methods:
- `search(query)` - quick name search
- `getAppDetails(appid)` - full game details
- `getAppList()` - paginated list of all Steam apps
- `extractGameFields(appid, data)` - cleans up a Steam API response and returns only the fields we need

**IsThereAnyDealService**
Wraps calls to the IsThereAnyDeal API to get price history for a specific game. The price history is displayed as a chart on the game detail page.

**SearchService**
The main search logic. Works in this order:
1. Search the local database using FULLTEXT search
2. If fewer than 5 results found, call the Steam search API
3. Save the new results to the database
4. If games are missing images or prices, fetch full details from Steam and update them
5. Return up to 15 results

---

## Database

The database has these tables:

**users** - Standard Laravel user table with name, email, and password fields.

**wishlists** - Links users to games. Each row is one game saved by one user.

---

## How the search works

1. User types in the search bar
2. JavaScript calls `/api/search?query=...`
3. SearchController calls SearchService
4. SearchService searches the local database with FULLTEXT (fast, uses MySQL index)
5. If there are fewer than 5 results, SearchService calls the Steam search API
6. New games from Steam are saved to the local database
7. Games that are missing images or prices get their details filled in from a second Steam API call
8. Results are returned as JSON (max 15 games)
9. JavaScript renders the results on the page

On the second search for the same game, the data is already in the database so it is instant.

---

## How the homepage data works

1. The page loads instantly showing animated placeholder boxes (skeleton loaders)
2. JavaScript calls `/api/home-data`
3. Laravel calls `https://store.steampowered.com/api/featuredcategories`
4. This public Steam URL returns top sellers, specials (discounted games), and coming soon games
5. Laravel cleans the data and returns only what the frontend needs
6. JavaScript replaces the placeholder boxes with real game cards

---

## Blade components

Reusable UI pieces in `resources/views/components/`:

| Component | Where it is used |
|---|---|
| `navbar` | Every page (in the layout) |
| `footer` | Every page (in the layout) |
| `button` | Various pages for styled buttons |
| `game-card` | Search results, game listings |
| `game-list-item` | Homepage most played section |
| `trending-item` | Homepage trending section |
| `upcoming-card` | Homepage upcoming section |
| `section-title` | Section headers with labels |

---

## Tests

Tests are in the `tests/` folder and use the Pest framework.

Run all tests with:

```bash
php artisan test
```

**Unit tests** (`tests/Unit/SearchSystemTest.php`):
Tests for SteamService and SearchService. These tests do not make real API calls. They use fake HTTP responses to simulate what Steam would return.

**Feature tests** (`tests/Feature/SteamWishTest.php`):
Tests for the actual HTTP routes. Checks that each page returns the right status code, validation works on the contact form, and the search API returns the correct JSON structure.

There are 23 tests total, all passing.

---

## Folder structure overview

```
app/
  Http/Controllers/   - All controllers
  Models/             - Game, User, Wishlist
  Services/           - SteamService, IsThereAnyDealService, SearchService

database/
  migrations/         - Database table definitions

resources/
  views/
    layouts/          - Base HTML layout (app.blade.php)
    components/       - Reusable UI pieces
    pages/            - One file per page

routes/
  web.php             - All URL routes

tests/
  Unit/               - Service-level tests
  Feature/            - Route-level tests
```

---

## What is not implemented yet

- Redis caching for search results
- Scheduled jobs to keep game prices updated automatically
- Price drop notifications for wishlist games
- Price history stored in the database (comes from ITAD API for now)
- Full user profile page
- The dashboard page has placeholder content
