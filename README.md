# Ekahal Products Assignment

A Laravel 13 + Breeze (Blade) web application implementing authentication, role-based authorization, product CRUD, search/filtering, and Docker-based deployment automation.

## Features Implemented

- Authentication: Laravel Breeze login/register/password flows.
- Role-Based Access Control:
- `admin` users can view/manage all products.
- `standard` users can only view/manage their own products.
- Product CRUD with strict server-side validation for:
- `title`
- `description` (rich-text HTML input, sanitized server-side)
- `price`
- `date_available`
- Search & filtering by keyword (`title`, `description`) with pagination.
- Deployment automation using Docker Compose + `deploy.sh`.

## Architecture Decisions

I structured the code to keep each layer focused on one responsibility:

- Controller layer (`app/Http/Controllers/ProductController.php`): handles HTTP flow only (request in, response out).
- Request layer (`app/Request/ProductStoreRequest.php`, `app/Request/ProductUpdateRequest.php`): centralizes validation rules.
- Service layer (`app/Service/ProductService.php`): contains business logic and input sanitization.
- Repository layer (`app/Repositories/Contracts/ProductRepositoryInterface.php`, `app/Repositories/Eloquent/ProductRepository.php`): handles database querying and persistence.
- Policy layer (`app/Policies/ProductPolicy.php`): enforces authorization rules.

This makes the code easier to maintain, easier to test, and safer to extend as the project grows.

## Security & Performance

### SQL Injection

- Eloquent query builder with parameter binding is used throughout repository queries.
- No raw string-concatenated SQL execution paths.

### XSS

- Product description input is sanitized in service layer via strict HTML tag allowlist and script/style stripping before persistence.
- Blade escaping is used by default. Only sanitized rich description is rendered as HTML.

### CSRF

- All state-changing forms include `@csrf` token and use Laravel middleware defaults.

### Authorization

- Policy enforcement through explicit `authorize(...)` checks and `ProductPolicy` prevents horizontal privilege escalation.

### Search Optimization

- Database indexes added for frequent filters/sorts:
- `title`
- `date_available`
- `user_id + date_available`
- `FULLTEXT(title, description)` for scalable keyword search support on MySQL.

## Challenges & Solutions

- Rich text vs XSS risk:
Allowed a small set of formatting tags for `description`, but sanitized all input server-side and removed dangerous scripts/styles before saving.
- Ownership and URL tampering edge cases:
Handled this with two protections: policy checks for each action and repository scoping so standard users only see their own records.
- Invalid/abusive input:
Applied strict Form Request validation (required fields, lengths, numeric constraints, date constraints) before any write operations.
- Search edge cases:
Normalized and sanitized search input (trim, strip tags, length limit) to avoid noisy queries and keep behavior stable.
- Scaling concerns:
Added indexes and FULLTEXT support to keep common search/filter queries performant as record counts grow.

## Deployment (Docker)

### Prerequisites

- Docker + Docker Compose installed.

### Run

1. `docker compose up --build`
2. Open `http://localhost:8000`

The `deploy.sh` script automatically performs:

- `.env` bootstrap/configuration
- `composer install`
- app key generation
- migrations + seed
- app startup

### Seeded Users

- Admin:
- email: `admin@example.com`
- password: `password`
- Standard:
- email: `user@example.com`
- password: `password`

## Local (Without Docker)

1. `composer install`
2. copy `.env.example` to `.env`
3. configure DB credentials
4. `php artisan key:generate`
5. `php artisan migrate --seed`
6. `php artisan serve`

## Future Improvements

If this were a real large-scale SaaS product, I would add:

1. Full automated test coverage (feature, policy, unit) with CI/CD quality gates.
2. Dedicated HTML sanitization library + strict Content Security Policy (CSP) and security headers.
3. Audit trail/event logging for every critical action (create/update/delete/login/security events).
4. Advanced search stack (Meilisearch/Elasticsearch) with relevance tuning and analytics.
5. API-first architecture with versioning, token-based access, and rate limiting.
6. Background jobs for heavy tasks and observability (metrics, tracing, alerting).
