# CoinWatch API

A production-ready REST API built with **Laravel 12** for tracking cryptocurrency prices and managing personalized price alerts. The system periodically fetches live market data from the **CoinGecko API**, caches it to minimize database load, and automatically evaluates user-defined price thresholds in the background via Laravel Queues and Scheduler.

---

## Key Features

- **Token Authentication:** Secure endpoints protected by Laravel Sanctum using Bearer tokens.
- **Price Alerts:** Users can create, list (paginated), and delete price alerts for any cryptocurrency with a configurable `direction` (`above` / `below`) and `target_price`.
- **CoinGecko Integration:** A scheduled Artisan command periodically fetches current market prices from the CoinGecko public API. The integration includes error handling (try-catch, timeouts) to gracefully handle network failures without crashing the application.
- **Market Data Endpoint:** A public `/api/market` endpoint returns the latest cached cryptocurrency prices, using the database cache driver to avoid redundant external API calls.
- **Queue-Based Alert Processing:** Alert evaluation runs as background jobs via Laravel Queues — thresholds are checked without blocking incoming requests.
- **High Test Coverage:** Test suite written in Pest PHP with Unit and Feature tests covering authentication, validation, and alert logic.

---

## Technology Stack

| Layer | Technology |
|---|---|
| Backend Framework | Laravel 12 (PHP 8.2+) |
| Authentication | Laravel Sanctum (Bearer tokens) |
| Testing | Pest PHP |
| Database | MySQL 8.4 (via Docker) |
| Cache driver | Database |
| Queue driver | Database |
| External Price API | CoinGecko |
| Environment | Laravel Sail (Docker) |

---

## Setup & Installation

### 1. Clone the repository

```bash
git clone https://github.com/Kamyqq/coinwatch-api.git
cd coinwatch-api
```

### 2. Install PHP dependencies

If you don't have PHP installed locally, use Docker:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

### 3. Configure the environment file

```bash
cp .env.example .env
```

Open `.env` and update the database settings to use MySQL (matching the Docker services in `compose.yaml`):

```dotenv
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

QUEUE_CONNECTION=database
CACHE_STORE=database
```

### 4. Start the Sail environment

```bash
./vendor/bin/sail up -d
```

The API will be available at **`http://localhost`** (port 80).

### 5. Generate the application key and run migrations

```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

The seeder creates a test user and populates the `cryptocurrencies` table with initial coins. After seeding, a **Bearer token** is printed in the console — copy it into `api.http` to start testing authenticated endpoints immediately.

### 6. Start the queue worker

The alert system dispatches jobs to a queue. **You must run the queue worker** in a separate terminal, otherwise price alerts will never be evaluated:

```bash
./vendor/bin/sail artisan queue:listen --tries=1 --timeout=0
```

### 7. Start the task scheduler

The scheduler is responsible for periodically triggering the CoinGecko price fetch command and checking user alerts. **Without it, the application will never pull new prices from the outside world and alerts will never fire.** Run this in another separate terminal:

```bash
./vendor/bin/sail artisan schedule:work
```

> In production, replace `schedule:work` with a single cron entry calling `schedule:run` every minute, as described in the [Laravel documentation](https://laravel.com/docs/scheduling#running-the-scheduler).

---

## API Endpoints

Base URL: `http://localhost/api`

All alert endpoints require an `Authorization: Bearer <token>` header. The market endpoint is public.

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/market` | ❌ | Returns all tracked cryptocurrency prices (cached) |
| `GET` | `/alerts` | ✅ | Lists all alerts for the authenticated user (paginated) |
| `POST` | `/alerts` | ✅ | Creates a new price alert |
| `DELETE` | `/alerts/{id}` | ✅ | Deletes a specific alert |

### Create alert — request body

```json
{
  "cryptocurrency_id": 1,
  "target_price": 20000.00,
  "direction": "below"
}
```

`direction` accepts `"above"` or `"below"`.

---

## API Testing (HTTP Client)

The repository includes an `api.http` file compatible with JetBrains HTTP Client and the VS Code REST Client extension.

After running the seeder, paste your Bearer token at the top of the file:

```
@token = your_token_here
@host = http://localhost/api
```

The file contains ready-to-use requests for all endpoints.

---

## Testing

The test suite uses Pest PHP and covers:

- **Authentication:** Verifying that unauthenticated requests are rejected (HTTP 401).
- **Validation:** Ensuring malformed alert payloads are rejected (HTTP 422).
- **Alert Logic:** Unit tests verifying threshold comparison and alert dispatch.
- **Caching:** Confirming that the market endpoint serves cached responses correctly.

Run the full test suite:

```bash
./vendor/bin/sail pest
```

> Tests use an in-memory SQLite database and a synchronous queue driver configured in `phpunit.xml` — no additional setup required.

---

## Quick-Start Checklist

| Step | Command / Action |
|---|---|
| Install dependencies | `composer install` (via Docker if needed) |
| Copy env file | `cp .env.example .env` |
| Set DB to MySQL | Update `DB_*` vars in `.env` |
| Set `QUEUE_CONNECTION` | `database` in `.env` ✅ |
| Set `CACHE_STORE` | `database` in `.env` ✅ |
| Start Sail | `./vendor/bin/sail up -d` |
| Run migrations & seed | `./vendor/bin/sail artisan migrate --seed` |
| **Start queue worker** | `./vendor/bin/sail artisan queue:listen --tries=1 --timeout=0` ⚠️ |
| **Start scheduler** | `./vendor/bin/sail artisan schedule:work` ⚠️ |
| Copy Bearer token | Paste token from seeder output into `api.http` |
| Run tests | `./vendor/bin/sail pest` |
