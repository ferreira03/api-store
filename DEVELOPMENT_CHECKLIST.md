# ✅ Development Checklist – API STORE

This file serves as a progress control for the implementation of the `API STORE` in PHP. All tasks are organized by technical and functional modules. When a task is completed (code, tests, and documentation), it should be marked with `[*]`. Tasks not yet done remain with `[ ]`.

---

## 🛠️ Setup Module

Tasks:
[*] Setup Docker (docker-compose.yml, Dockerfile)
[*] Define initial folder structure (`src/`, `public/`, `tests/`, etc.)
[*] Setup Composer with PSR-4 autoload
[*] Install initial dependencies (FastRoute, PHP-DI, etc.)
[*] Basic routing setup
[*] Install and configure PHPStan for static analysis
[*] Install and configure PHP-CS-Fixer for code style

---

## 🔐 Auth Module (Bearer Token)

Tasks:
[*] Implement `AuthMiddleware.php` for token validation
[*] Create config file (`config/auth.php`) to store token
[*] Protect sensitive routes (POST, PUT, DELETE)
[*] Create unit tests for authentication
[*] Document authentication in Swagger + README

---

## 🧱 Core Domain – Store (Magasin)

Tasks:
[*] Create Store Entity
[*] Create Store Repository (interface and implementation)
[*] Create Store Service (business logic)
[*] Create Store Controller (expose REST routes)
[*] Create input validations
[*] Implement persistence with MySQL
[ ] Create unit tests for each layer (Entity ✅, Repo ✅, Service ✅, Controller❌)

---

## 🚦 API Routes & Business Logic

Tasks:
[*] Implement `GET /stores` route (list)
[*] Implement `GET /stores?filter=...&sort=...` route
[*] Implement `POST /stores` route (create)
[*] Implement `PUT /stores/{id}` route (edit)
[*] Implement `DELETE /stores/{id}` route (delete)
[*] Implement `PATCH /stores/{id}` route (partial update)
[ ] Tests for each route (PHPUnit ✅+ integration❌)
[*] Document all routes in Swagger

---

## 📊 Observability & Logs

Tasks:
[ ] Install and configure Monolog
[ ] Create generic `LoggerService`
[ ] Inject logger into main services
[ ] Generate logs by level (INFO, WARNING, ERROR)
[ ] Test log generation and formatting
[ ] Document log structure in README

---

## 🧪 Testing Module

Tasks:
[*] Setup PHPUnit (phpunit.xml, bootstrap)
[*] Create base unit tests
[ ] Create integration tests for REST routes
[*] Mock dependencies (repository, logger)
[ ] Run tests via Docker
[ ] Ensure minimum coverage of 80%

---

## 📁 Documentation & Standards

Tasks:
[*] Write `README.md`
[*] Write `architecture.md`
[*] Write `best-practices.md`
[*] Write `technical-stack.md`
[*] Write `folder-structure.md`
[*] Write `testing.md`
[*] Write `api-documentation.md` with Swagger

---

## 🚀 Final Steps

Tasks:
[ ] Check test coverage
[ ] Check logs and errors in container
[ ] Validate response from all endpoints
[*] Validate Swagger documentation via browser
[*] Validate API running with complete Docker setup

---

📌 **Note:** This file must be kept up to date. A task should only be marked as completed `[*]` when the code + tests + documentation (when applicable) have been delivered. 