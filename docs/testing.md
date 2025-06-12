# Testing Documentation for API STORE

## Overview
This document outlines the testing strategy and practices for the API STORE project. We use PHPUnit as our primary testing framework and follow a comprehensive testing approach.

## Important Note
All tests must be run inside Docker containers to ensure consistent environment and dependencies. This ensures that all developers and CI/CD pipelines are using the same environment for testing.

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
```

## Test Data

n

## Running Tests

### Via Docker (Required)
All tests must be executed inside the Docker container to ensure environment consistency:

```bash
# Run all tests
docker-compose exec php vendor/bin/phpunit 

# Run specific test file
docker-compose exec php vendor/bin/phpunit tests/Unit/YourTest.php

# Run tests with specific filter
docker-compose exec php vendor/bin/phpunit --filter TestName
```

## Best Practices

## CI/CD and QA Checks

### GitHub Actions Workflow
The project uses GitHub Actions for continuous integration and quality assurance. The QA workflow runs automatically on:
- Push to `main` and `develop` branches
- Pull requests to `main` and `develop` branches

### QA Process Steps
1. **Environment Setup**
   - Uses Ubuntu latest
   - PHP 8.2 with required extensions
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
docker-compose exec php vendor/bin/php-cs-fixer fix --dry-run --diff

# Run PHPStan
docker-compose exec php vendor/bin/phpstan analyse src tests
```

### Required Extensions
The QA environment requires the following PHP extensions:
- mbstring
- xml
- ctype
- iconv
- intl
- pdo_mysql
- json
- curl
