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

- Service Layer: business logic is in `app/Service/ProductService.php`.
- Repository Pattern:
- contract: `app/Repositories/Contracts/ProductRepositoryInterface.php`
- implementation: `app/Repositories/Eloquent/ProductRepository.php`
- Thin Controller: `app/Http/Controllers/ProductController.php` delegates to service and handles only request/response.
- Dedicated Request namespace in `app/Request`:
- `app/Request/ProductStoreRequest.php`
- `app/Request/ProductUpdateRequest.php`
- Policy-based authorization: `app/Policies/ProductPolicy.php`.

This structure keeps controllers minimal, enables focused unit/feature testing, and avoids mixing persistence/query concerns with business rules.

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

- Policy enforcement via `authorizeResource` and `ProductPolicy` prevents horizontal privilege escalation.

### Search Optimization

- Database indexes added for frequent filters/sorts:
- `title`
- `date_available`
- `user_id + date_available`
- `FULLTEXT(title, description)` for scalable keyword search support on MySQL.

## Challenges & Solutions

- Rich text vs XSS risk:
- Allow minimal formatting tags while sanitizing dangerous tags/scripts server-side.
- Multi-tenant ownership constraints:
- Centralized ownership checks in policy and query scoping in repository to avoid accidental data leakage.
- Consistent validation:
- Enforced strict rules in Form Requests, not in controllers.

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

1. Add audit logging for all critical CRUD actions.
2. Replace basic HTML sanitization with a dedicated sanitizer package and strict CSP headers.
3. Add full test suite (feature + policy + service unit tests) and CI pipeline.
4. Move search to dedicated engine (Meilisearch/Elasticsearch) for large datasets.
5. Add API layer with token auth and versioning for external integrations.
