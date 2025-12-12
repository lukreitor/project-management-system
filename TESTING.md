# Testing Instructions

## Running Tests Locally

### Prerequisites
1. Docker and Docker Compose installed
2. All containers running

### Steps to Run Tests

1. **Start Docker containers:**
```bash
docker-compose up -d
```

2. **Install Composer dependencies:**
```bash
docker-compose exec app composer install
```

3. **Copy environment file:**
```bash
docker-compose exec app cp .env.example .env
```

4. **Generate application key:**
```bash
docker-compose exec app php artisan key:generate
```

5. **Run all tests:**
```bash
docker-compose exec app php artisan test
```

Or using PHPUnit directly:
```bash
docker-compose exec app vendor/bin/phpunit
```

### Test Structure

**Unit Tests (10 tests):**
- `tests/Unit/ProgressCalculationServiceTest.php`
  - Progress calculation logic
  - Effort points validation
  - Weighted calculation with mixed difficulties

**Feature Tests (21 tests):**
- `tests/Feature/ProjectTest.php` - Project endpoints
- `tests/Feature/TaskTest.php` - Task endpoints

### Expected Results

All 31 tests should pass:
- ✓ Unit tests: Progress calculation logic
- ✓ Feature tests: Project API endpoints
- ✓ Feature tests: Task API endpoints

### Troubleshooting

**If tests fail:**

1. **Database connection issues:**
```bash
docker-compose exec app php artisan migrate:fresh
```

2. **Factory issues:**
   - Check that models have `use HasFactory;` trait
   - Verify factory files exist in `database/factories/`

3. **Namespace issues:**
   - Run `composer dump-autoload`

4. **SQLite not installed:**
   - Tests use SQLite in-memory database
   - PHPUnit configuration: `backend/phpunit.xml`

## CI/CD Integration

For GitHub Actions or similar CI/CD:

```yaml
- name: Run tests
  run: |
    docker-compose exec -T app composer install
    docker-compose exec -T app php artisan test
```

## Test Coverage (Optional)

Generate code coverage report:
```bash
docker-compose exec app vendor/bin/phpunit --coverage-html coverage
```
