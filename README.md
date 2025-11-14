# Laravel Microservice Template

<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
</p>

<p align="center">
<img src="https://img.shields.io/badge/Laravel-12-red" alt="Laravel 12">
<img src="https://img.shields.io/badge/PHP-8.3-blue" alt="PHP 8.3">
<img src="https://img.shields.io/badge/Architecture-Clean-green" alt="Clean Architecture">
<img src="https://img.shields.io/badge/Docker-Ready-blue" alt="Docker">
<img src="https://img.shields.io/badge/License-MIT-green" alt="License">
</p>

## 🚀 Production-Ready Laravel 12 Microservice Template

A **production-ready**, **scalable**, and **maintainable** microservice template built with **Laravel 12**, following **Clean Architecture**, **PSR-4**, and **Microservices best practices**.

This template provides a solid foundation for building modern microservices with standardized responses, correlation ID tracking, comprehensive error handling, and Docker support.

---

## ✨ Features

### 🏗️ Architecture

-   ✅ **Clean Architecture** - Separation of concerns with clear layer boundaries
-   ✅ **PSR-4 Autoloading** - Follows PHP-FIG standards
-   ✅ **SOLID Principles** - Maintainable and testable code
-   ✅ **Repository Pattern** - Data access abstraction
-   ✅ **Action Pattern** - Use-case implementations
-   ✅ **DTO Pattern** - Type-safe data transfer

### 🔧 Core Features

-   ✅ **Unified Response Format** - Standardized API responses
-   ✅ **Correlation ID Tracking** - Request tracing across services
-   ✅ **Comprehensive Exception Handling** - Centralized error management
-   ✅ **Health & Version Endpoints** - Service monitoring
-   ✅ **HTTP Client Wrapper** - External service communication with retry logic
-   ✅ **Event-Driven Architecture** - Domain events support

### 🐳 DevOps & Tools

-   ✅ **Docker & Docker Compose** - Containerized environment
-   ✅ **Makefile** - Simplified command execution
-   ✅ **Laravel Pint** - Code formatting
-   ✅ **PHPStan Level 6** - Static analysis
-   ✅ **Pest/PHPUnit Testing** - Comprehensive test suite
-   ✅ **Git Hooks** - Pre-commit quality checks

---

## 📋 Table of Contents

-   [Quick Start](#-quick-start)
-   [Architecture](#-architecture)
-   [Project Structure](#-project-structure)
-   [API Endpoints](#-api-endpoints)
-   [Development](#-development)
-   [Testing](#-testing)
-   [Deployment](#-deployment)
-   [Documentation](#-documentation)

---

## 🚀 Quick Start

### Prerequisites

-   Docker & Docker Compose
-   Git
-   Make (optional but recommended)

### Installation

```bash
# Clone the repository
git clone https://github.com/your-org/laravel-microservice-template.git my-service
cd my-service

# Copy environment file
cp .env.example .env

# Install dependencies and start containers (using Make)
make setup

# Or manually:
composer install
docker-compose up -d --build
docker-compose exec php-fpm php artisan migrate
```

### Verify Installation

```bash
# Health check
curl http://localhost:8000/api/health

# Version info
curl http://localhost:8000/api/version
```

---

## 🏗️ Architecture

This template follows **Clean Architecture** principles with the following layers:

```
┌─────────────────────────────────────────┐
│             Controllers (HTTP)              │
├─────────────────────────────────────────┤
│            Actions (Use Cases)              │
├─────────────────────────────────────────┤
│          Services (Business Logic)          │
├─────────────────────────────────────────┤
│          Repositories (Data Access)         │
├─────────────────────────────────────────┤
│          Models (Database Entities)         │
└─────────────────────────────────────────┘
```

### Layer Responsibilities

| Layer            | Purpose                        | Example            |
| ---------------- | ------------------------------ | ------------------ |
| **Controllers**  | Handle HTTP requests/responses | `HealthController` |
| **Actions**      | Implement use cases            | `YourAction`       |
| **DTOs**         | Transfer data between layers   | `YourDTO`          |
| **Services**     | Business logic orchestration   | `BaseService`      |
| **Repositories** | Data access abstraction        | `BaseRepository`   |
| **Models**       | Database entities              | `User`             |
| **Contracts**    | Interface definitions          | `ActionContract`   |

📖 **Detailed Architecture**: See [docs/architecture.md](docs/architecture.md)

---

## 📁 Project Structure

```
app/
├── Actions/                 # Use-case implementations
├── Clients/                 # HTTP clients for external services
├── Contracts/              # Interfaces (PSR-4)
│   ├── Actions/
│   ├── Services/
│   ├── Repositories/
│   ├── Clients/
│   └── Events/
├── DTO/                    # Data Transfer Objects
├── Events/                 # Domain events
│   └── Publishers/
├── Exceptions/             # Custom exceptions
├── Http/
│   ├── Controllers/        # Request handlers
│   └── Middleware/
├── Models/                 # Eloquent models
├── Providers/              # Service providers
├── Repositories/           # Data access layer
├── Services/               # Business logic
└── Traits/                 # Reusable traits

config/
└── service.php            # Service configuration

docker/
├── Dockerfile             # Multi-stage PHP-FPM
└── nginx/                 # Nginx configuration

docs/
├── architecture.md        # Architecture overview
├── folder-structure.md    # Detailed structure
└── how-to-create-new-service.md
```

📖 **Full Structure**: See [docs/folder-structure.md](docs/folder-structure.md)

---

## 🌐 API Endpoints & Versioning

### API Versioning

This microservice implements **enterprise-grade API versioning** to support multiple API versions simultaneously and enable backward-compatible evolution.

**Current Version**: `v1` (default)

All versioned endpoints are prefixed with `/api/v{version}/`:

```
/api/v1/your-endpoint
/api/v1/another-endpoint
```

Non-versioned core endpoints:

```
/api/health
/api/version
```

### Core Endpoints (Non-Versioned)

| Method | Endpoint       | Description                      |
| ------ | -------------- | -------------------------------- |
| GET    | `/api/health`  | Service health check with status |
| GET    | `/api/version` | Service and version information  |

### Version 1 Endpoints

Currently empty - add your versioned endpoints here:

```php
// In routes/api_v1.php
Route::get('/your-endpoint', [YourController::class, 'method'])->name('v1.your-endpoint');
```

### Versioning Strategy

**URL-Based Versioning**: Version is specified in the URL path (`/api/v1/`, `/api/v2/`)

**Benefits**:

-   ✅ Gateway-friendly (easy routing at API gateway level)
-   ✅ Explicit and self-documenting
-   ✅ Supports multiple versions simultaneously
-   ✅ Enables gradual deprecation and migration
-   ✅ Cache-friendly

**Configuration**: Managed in `config/api.php`

```php
'available_versions' => ['v1'],
'default_version' => 'v1',
```

### Version-Specific Documentation

Each API version has its own OpenAPI specification:

-   **v1 Documentation**: `docs/openapi/v1.yaml`
-   **Swagger UI v1**: [http://localhost:8000/docs/v1](http://localhost:8000/docs/v1) _(when implemented)_

### Adding New API Versions

1. **Create version route file**:

    ```bash
    touch routes/api_v2.php
    ```

2. **Register in `routes/api.php`**:

    ```php
    Route::prefix('v2')->group(base_path('routes/api_v2.php'));
    ```

3. **Create versioned controllers**:

    ```bash
    mkdir app/Http/Controllers/Api/V2
    ```

4. **Update `config/api.php`**:

    ```php
    'available_versions' => ['v1', 'v2'],
    'default_version' => 'v2',
    ```

5. **Create OpenAPI spec**: `docs/openapi/v2.yaml`

### Version Deprecation

Mark versions as deprecated in `config/api.php`:

```php
'deprecated_versions' => [
    'v1' => '2026-01-01',
],
```

Deprecated versions will include warning headers in responses.

### Response Format

All endpoints return a standardized JSON response:

```json
{
    "success": true,
    "data": {},
    "message": "",
    "code": 200,
    "errors": [],
    "correlation_id": "uuid"
}
```

### Gateway Integration

This versioning structure is designed to work seamlessly with API gateways:

```
API Gateway
├── /v1/auth → Auth Service v1
├── /v1/booking → Booking Service v1
├── /v1/payment → Payment Service v1
└── /v1/notification → Notification Service v1
```

The gateway can route based on URL prefix without version header parsing.

---

## 💻 Development

### Using Make Commands

```bash
make help          # Show all available commands
make install       # Install dependencies
make start         # Start Docker containers
make stop          # Stop containers
make restart       # Restart containers
make test          # Run tests
make format        # Format code with Pint
make analyse       # Run PHPStan analysis
make logs          # View container logs
make shell         # Access PHP container
make clean         # Clear cache
```

### Manual Commands

```bash
# Start containers
docker-compose up -d

# Run tests
docker-compose exec php-fpm php artisan test

# Format code
docker-compose exec php-fpm ./vendor/bin/pint

# Static analysis
docker-compose exec php-fpm ./vendor/bin/phpstan analyse

# Access container
docker-compose exec php-fpm bash

# View logs
docker-compose logs -f php-fpm
```

### Creating New Features

1. **Create a DTO**

    ```bash
    php artisan make:class DTO/YourDTO
    ```

2. **Create a Contract**

    ```bash
    php artisan make:class Contracts/Actions/YourActionContract
    ```

3. **Create an Action**

    ```bash
    php artisan make:class Actions/YourAction
    ```

4. **Create a Controller**

    ```bash
    php artisan make:controller YourController
    ```

5. **Add Routes** in `routes/api.php`

6. **Write Tests**
    ```bash
    php artisan make:test YourFeatureTest
    ```

---

## 🧪 Testing

### Run All Tests

```bash
make test
# or
php artisan test
```

### Run Specific Test

```bash
php artisan test --filter=ExampleTest
```

### With Coverage

```bash
php artisan test --coverage
```

### Test Structure

```
tests/
├── Feature/           # Feature/integration tests
│   └── ExampleTest.php
└── Unit/             # Unit tests
    └── ExampleTest.php
```

---

## 🚢 Deployment

### Docker Production Build

```bash
# Build production image
docker build -t my-service:latest -f docker/Dockerfile --target production .

# Run container
docker run -d -p 80:9000 my-service:latest
```

### Environment Configuration

```bash
# Copy and configure .env for production
cp .env.example .env.production

# Update these variables:
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generate-with-artisan>
DB_HOST=<production-db-host>
REDIS_HOST=<production-redis-host>
```

### Optimization

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

### Deployment Checklist

-   [ ] Update `.env` for production
-   [ ] Run database migrations
-   [ ] Set up queue workers
-   [ ] Configure monitoring/logging
-   [ ] Set up CORS if needed
-   [ ] Enable HTTPS
-   [ ] Configure rate limiting
-   [ ] Set up backups
-   [ ] Test health endpoints

---

## 📚 Documentation

### Available Documentation

-   **[Architecture Overview](docs/architecture.md)** - System design and patterns
-   **[Folder Structure](docs/folder-structure.md)** - Project organization
-   **[How to Create New Service](docs/how-to-create-new-service.md)** - Step-by-step guide

---

## 🔧 Configuration

### Service Configuration

Edit `config/service.php`:

```php
return [
    'service_name' => env('SERVICE_NAME', 'laravel-microservice'),
    'service_version' => env('SERVICE_VERSION', '1.0.0'),
];
```

### Correlation ID

All requests automatically receive a correlation ID for distributed tracing:

-   Generated if not provided
-   Logged with every log entry
-   Included in all responses
-   Forwarded to external services

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests and code quality checks
5. Submit a pull request

### Code Quality Standards

```bash
# Format code
make format

# Run static analysis
make analyse

# Run tests
make test
```

---

## 📝 License

This template is open-sourced software licensed under the [MIT license](LICENSE).

---

## 🙏 Credits

Built with:

-   [Laravel 12](https://laravel.com)
-   [PHP 8.3](https://www.php.net)
-   [Docker](https://www.docker.com)

---

## 📚 API Documentation

This microservice template uses **OpenAPI 3.1** with version-specific specifications:

-   `docs/openapi/v1.yaml` - API v1 specification
-   `docs/openapi/v2.yaml` - API v2 specification

**Dynamic Versioning**: The OpenAPI service automatically loads the correct specification based on the requested version.

### Swagger UI (Version-Specific)

Access documentation for specific API versions:

| Version          | Swagger UI                                | OpenAPI Spec (JSON)                                       |
| ---------------- | ----------------------------------------- | --------------------------------------------------------- |
| **v1**           | [/docs/v1](http://localhost:8000/docs/v1) | [/openapi/v1.json](http://localhost:8000/openapi/v1.json) |
| **v2**           | [/docs/v2](http://localhost:8000/docs/v2) | [/openapi/v2.json](http://localhost:8000/openapi/v2.json) |
| **Default (v1)** | [/docs](http://localhost:8000/docs)       | [/openapi/v1.json](http://localhost:8000/openapi/v1.json) |

### Updating API Documentation

When you add or modify API endpoints:

1. Edit the version-specific file (e.g., `docs/openapi/v1.yaml`)
2. Define request/response schemas in the `components` section
3. Add new paths with proper descriptions and examples
4. The documentation will automatically update when you refresh `/docs/v1`
5. Service metadata (name, version) is automatically replaced from `config/service.php`

### Adding New API Version Documentation

```bash
# Copy existing spec as template
cp docs/openapi/v1.yaml docs/openapi/v2.yaml

# Edit the new spec
# Update info.title, info.version, servers[*].url
# Add/modify v2-specific endpoints
```

The OpenAPI specification follows industry standards and can be used with any OpenAPI-compatible tools.

---

## 🔐 JWT Verification

This microservice template includes a **complete JWT verification layer** for authenticating requests.

**Important**: This service does **NOT** issue tokens. Tokens are issued by a separate authentication service.

### Setup

1. **Install the required package:**

```bash
composer require firebase/php-jwt
```

2. **Obtain the public key** from your authentication service and place it in:

```
keys/public.pem
```

3. **Configure the JWT settings** in your `.env` file:

```env
JWT_PUBLIC_KEY_PATH=./keys/public.pem
```

### Protecting Routes

Use the `jwt` middleware to protect your API routes:

```php
// In routes/api.php
Route::middleware('jwt')->group(function () {
    Route::get('/protected-endpoint', [YourController::class, 'method']);
});
```

### Accessing JWT Payload

After successful verification, the JWT payload is attached to the request:

```php
public function myMethod(Request $request)
{
    $userId = $request->attributes->get('user_id');
    $fullPayload = $request->attributes->get('jwt_payload');

    // Use the authenticated user data
}
```

### JWT Response Format

All JWT authentication errors return a standardized JSON response:

```json
{
    "success": false,
    "message": "Unauthorized",
    "code": 401,
    "errors": [],
    "correlation_id": "550e8400-e29b-41d4-a716-446655440000"
}
```

### Helper Function

A global helper function is available for extracting bearer tokens:

```php
$token = extractBearerToken($request);
```

### Testing

JWT verification tests are included:

```bash
php artisan test --filter=JwtVerifierTest
```

---

## 📞 Support

For issues and questions:

-   Check the [documentation](docs/)
-   Review the [architecture guide](docs/architecture.md)
-   Consult [Laravel documentation](https://laravel.com/docs)

---

**Ready to build your microservice? 🚀**

```bash
make setup && make start
```
