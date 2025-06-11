# Testing Documentation for API STORE

## Overview
This document outlines the testing strategy and practices for the API STORE project. We use PHPUnit as our primary testing framework and follow a comprehensive testing approach.

## Test Types

### Unit Tests
- Test individual components in isolation
- Mock dependencies
- Focus on business logic
- Located in `tests/Unit/`

Example:
```php
class StoreServiceTest extends TestCase
{
    public function testCreateStoreWithValidData()
    {
        $storeData = [
            'name' => 'Test Store',
            'address' => '123 Test St'
        ];
        
        $result = $this->storeService->create($storeData);
        
        $this->assertInstanceOf(Store::class, $result);
        $this->assertEquals('Test Store', $result->getName());
    }
}
```

### Integration Tests
- Test component interactions
- Use test database
- Located in `tests/Integration/`

Example:
```php
class StoreControllerTest extends TestCase
{
    public function testCreateStoreEndpoint()
    {
        $response = $this->post('/api/v1/stores', [
            'name' => 'Test Store',
            'address' => '123 Test St'
        ]);
        
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }
}
```

## Test Structure

### Directory Structure
```
tests/
├── Unit/
│   ├── Services/
│   ├── Repositories/
│   └── Domain/
├── Integration/
│   ├── Controllers/
│   └── Services/
└── Fixtures/
```

### Test Naming
- `{ClassName}Test.php` for test classes
- `test{MethodName}With{Scenario}` for test methods

## Test Data

### Fixtures
- Use factory classes
- Create test data programmatically
- Reset data between tests

Example:
```php
class StoreFactory
{
    public static function create(array $attributes = []): Store
    {
        return new Store(array_merge([
            'name' => 'Test Store',
            'address' => '123 Test St'
        ], $attributes));
    }
}
```

### Database
- Use SQLite for testing
- Reset database before each test
- Use transactions for isolation

## Running Tests

### Via Docker
```bash
docker-compose exec php vendor/bin/phpunit
```

### Specific Test Suite
```bash
vendor/bin/phpunit --testsuite Unit
vendor/bin/phpunit --testsuite Integration
```

### With Coverage
```bash
vendor/bin/phpunit --coverage-html coverage/
```

## Code Coverage Requirements
- Minimum 80% coverage for unit tests
- Minimum 60% coverage for integration tests
- Critical paths must have 100% coverage

## Mocking

### Using PHPUnit Mocks
```php
$repository = $this->createMock(StoreRepository::class);
$repository->expects($this->once())
    ->method('find')
    ->with(1)
    ->willReturn(new Store());
```

### Using Prophecy
```php
$repository = $this->prophesize(StoreRepository::class);
$repository->find(1)->willReturn(new Store());
```

## Best Practices

### Test Organization
- One assertion per test
- Clear test names
- Arrange-Act-Assert pattern
- Use data providers for multiple scenarios

## CI/CD and QA Checks

### GitHub Actions Workflow
The project uses GitHub Actions for continuous integration and quality assurance. The QA workflow runs automatically on:
- Push to `main` and `develop` branches
- Pull requests to `main` and `develop` branches

### QA Process Steps
1. **Environment Setup**
   - Uses Ubuntu latest
   - PHP 8.1 with required extensions
   - Composer for dependency management

2. **Code Quality Checks**
   - PHP CS Fixer for code style validation
   - PHPStan for static analysis
   - Runs on source and test directories

3. **Dependency Management**
   - Caches Composer dependencies
   - Uses `--prefer-dist` for faster installation
   - Ensures consistent dependency versions

### Running QA Checks Locally
```bash
# Run PHP CS Fixer
vendor/bin/php-cs-fixer fix --dry-run --diff

# Run PHPStan
vendor/bin/phpstan analyse src tests
```

### Required Extensions
The QA environment requires the following PHP extensions:
- mbstring
- xml
- ctype
- iconv
- intl
- pdo_sqlite
