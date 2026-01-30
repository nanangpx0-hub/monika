# AI Coding Agent Instructions for MONIKA

**MONIKA** (Monitoring Nilai Kinerja & Anomali) is a PHP/CodeIgniter 4 web application for monitoring field officer performance and data quality through document management and anomaly tracking.

## Architecture Overview

### Tech Stack
- **Backend:** PHP 8.1+, CodeIgniter 4.x (MVC framework)
- **Frontend:** Bootstrap 4 + AdminLTE 3.2 template (server-side rendered views)
- **Database:** MySQL/MariaDB with timestamp tracking
- **Testing:** PHPUnit 10.5+ (minimal coverage currently)

### Core Components

```
app/
├── Controllers/    # Request handlers (Auth, Home, Dokumen, Kegiatan, Laporan, Monitoring)
├── Models/        # Data access layer with built-in validation & callbacks
├── Views/         # PHP template files organized by feature (auth, dashboard, dokumen, etc.)
├── Filters/       # Middleware - currently just AuthFilter for session-based auth
└── Config/        # Routes, Database, Services, and Framework configuration
```

### Critical Data Flow: Document Lifecycle

All business logic revolves around the **dokumen_survei** (document survey) lifecycle:

1. **Upload** (PCL/Admin) → Status: `Uploaded`, `created_at` recorded
2. **Entry Processing** (Pengolahan/Processor) → Mark as `Sudah Entry` OR report errors
3. **Error Logging** → Creates **anomali_log** entry with error details
4. **Final Status** → `Sudah Entry` (valid) or `Error` (returned for correction)

**Key insight:** The `pernah_error` flag on dokumen_survei tracks if a document was ever corrected, critical for performance metrics.

## Development Patterns

### Model Conventions
- All models extend `CodeIgniter\Model`
- Define `$allowedFields` explicitly (required for mass assignment)
- Use `$useTimestamps = true` with `$createdField` for audit trails
- Implement validation rules in `$validationRules` (inline, not external)
- Use Model callbacks (`beforeInsert`, `beforeUpdate`) for business logic like password hashing

Example:
```php
protected $allowedFields = ['fullname', 'username', 'email', 'password', ...];
protected $beforeInsert = ['hashPassword'];
protected $beforeUpdate = ['hashPassword'];
```

### Controller Conventions
- Extend `BaseController` (not raw Controller)
- Load models at top of class or inline in methods
- Use session for authentication state: `session()->get('is_logged_in')`, `session()->get('id_role')`
- Return views with data array: `view('feature/action', $data)`
- No middleware pattern yet—use filter directives in Routes instead

### Authentication & Authorization
- **Session-based** (not JWT): Check `session()->get('is_logged_in')`
- **Role-based access** via `id_role` in session (1=Admin, 3=PCL, 4=Processor, 5=PML, 6=PPL)
- **AuthFilter** on protected routes in [app/Config/Routes.php](app/Config/Routes.php)
- **User model** auto-hashes passwords via callback during insert/update

### Routing Patterns
Routes grouped by feature with `auth` filter:
```php
$routes->group('dokumen', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Dokumen::index');
    $routes->post('store', 'Dokumen::store');
    $routes->post('mark-entry/(:num)', 'Dokumen::markEntry/$1');
});
```
**Pattern:** RESTful actions (index, create, store, delete); POST for state changes.

### View Structure
- Main layout: [app/Views/layout/template.php](app/Views/layout/template.php) (header, sidebar, footer)
- Feature views in subdirectories: `dokumen/index.php`, `kegiatan/create.php`
- Flash messages via `session()->getFlashdata('success'/'error')`
- DataTables.js for list pages (dokumen, kegiatan) with export buttons

### Database Patterns
- **Relationships:** users ← dokumen_survei ← anomali_log; dokumen_survei → master_kegiatan
- **Status tracking:** dokumen_survei.status = ENUM('Uploaded', 'Sudah Entry', 'Error', 'Valid')
- **Audit fields:** All user-created/modified tables have `created_at`, `updated_at` (auto-managed by CI4)
- **No soft deletes** currently—permanent deletion via controller logic

## Critical Developer Workflows

### Local Setup
```bash
# Environment configuration
cp env .env  # Configure: app.baseURL, database credentials

# Dependencies
composer install

# Database initialization
# 1. Import schema: mysql -u root < monika_schema.sql
# 2. Seed data: php spark db:seed RoleSeeder # (if migrations exist)
```

### Running Tests
```bash
# Copy test configuration
cp phpunit.xml.dist phpunit.xml

# Run all tests
./phpunit

# Generate coverage report (HTML in tests/coverage/)
./phpunit --coverage-html=tests/coverage/ -d memory_limit=1024m
```

### Debugging
- Enable CodeIgniter toolbar in `.env`: `CI_ENVIRONMENT = development`
- Query logging: Check `writable/logs/` for CI4 logs
- No custom logger configured—use `log_message('error', $msg)` for CI4 logging

### Database Seeding
Two approaches coexist:
1. **Standard migrations** (placeholder in `app/Database/Migrations/`)
2. **Seed runners** (`seed_runner.php`, `large_seed_runner.php`) for bulk data generation using FakerPHP

**When adding test data:** Use existing seeders in `app/Database/Seeds/` or create new ones extending `Seeder` class.

## Key Files to Reference

- **Routes definition:** [app/Config/Routes.php](app/Config/Routes.php) — understand URL-to-controller mapping
- **Database schema:** [monika_schema.sql](monika_schema.sql) — tables, columns, constraints, relationships
- **Feature documentation:** [DOKUMENTASI_MONIKA.md](DOKUMENTASI_MONIKA.md) — detailed workflow and requirements
- **Frontend structure:** [docs/technical/frontend-architecture.md](docs/technical/frontend-architecture.md) — AdminLTE layout, DataTables integration
- **API reference:** [docs/technical/api-documentation.md](docs/technical/api-documentation.md) — endpoint specs and flow diagrams

## Project-Specific Conventions

1. **No explicit namespacing helpers** — use global functions in models/controllers (e.g., `session()`, `view()`, `redirect()`)
2. **Flash messages for user feedback** — always use `session()->setFlashdata()` for one-time notifications
3. **Query builder via models** — avoid raw SQL; use Model's `where()`, `first()`, `findAll()` methods
4. **CSRF protection disabled by default** — if enabling, add `<?= csrf_field() ?>` to all forms
5. **No external API calls** — system is standalone; all data lives in local MySQL database
6. **Minimal JavaScript** — prefer server-side rendering with AJAX for data updates; jQuery 3.6 already loaded

## Known Limitations & Gotchas

- **No automatic query cloning:** When reusing query builder instances, explicitly `clone` or rebuild (see [.kombai/debug/](../.kombai/debug/) notes)
- **Session file-based:** Not suitable for load balancing without session sharing
- **Timestamps mismatch:** Some models have only `created_at`—check `$useTimestamps` and `$updatedField` in model definitions
- **Minimal error handling:** Most error pages are generic 404/500 from CI4; add custom error views if needed
- **No type hints in older code** — gradually adding during refactoring; follow PSR-12 for new code

## When to Reference

- **Adding a new feature:** Check [DOKUMENTASI_MONIKA.md](DOKUMENTASI_MONIKA.md) for business rules first, then mimic existing controller patterns in [app/Controllers/](app/Controllers/)
- **Modifying database:** Update [monika_schema.sql](monika_schema.sql) AND create corresponding migrations in [app/Database/Migrations/](app/Database/Migrations/)
- **Styling UI:** Reference AdminLTE 3.2 docs and existing views in [app/Views/](app/Views/) for consistent layout
- **Debugging data issues:** Check [analisa_monika.md](analisa_monika.md) for known issues and [.kombai/debug/](../.kombai/debug/) for recent fixes
