# Logging Documentation for API STORE

## Overview
This document describes the logging system implemented in the API STORE project, including configuration, usage, and best practices.

## Logger Service

### Implementation
```php
namespace App\Services;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\JsonFormatter;

class LoggerService
{
    private Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger('api-store');
        
        $handler = new StreamHandler(
            __DIR__ . '/../../var/logs/app.log',
            Logger::DEBUG
        );
        
        $handler->setFormatter(new JsonFormatter());
        $this->logger->pushHandler($handler);
    }

    public function info(string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }
}
```

## Log Configuration

### Monolog Configuration
```php
// config/logging.php
return [
    'handlers' => [
        'file' => [
            'path' => __DIR__ . '/../../var/logs/app.log',
            'level' => 'debug',
            'bubble' => true,
        ],
        'error_file' => [
            'path' => __DIR__ . '/../../var/logs/error.log',
            'level' => 'error',
            'bubble' => false,
        ]
    ],
    'formatters' => [
        'json' => [
            'class' => JsonFormatter::class,
            'format' => "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
        ]
    ]
];
```

## Log Categories

### Information Logs
- Store creation
- Store updates
- Store deletion
- Successful operations

### Warning Logs
- Invalid input attempts
- Rate limit warnings
- Deprecated feature usage
- Performance warnings

### Error Logs
- Validation errors
- Authentication failures
- Database errors
- System errors

## Log Format

### JSON Format
```json
{
    "message": "Store created",
    "context": {
        "store_id": 123,
        "name": "Test Store",
        "user_id": "abc123"
    },
    "level": 200,
    "level_name": "INFO",
    "channel": "api-store",
    "datetime": "2024-03-20T12:00:00Z",
    "extra": {
        "request_id": "req-123",
        "ip": "127.0.0.1"
    }
}
```

### Text Format
```
[2024-03-20 12:00:00] api-store.INFO: Store created {"store_id":123,"name":"Test Store"} {"request_id":"req-123"}
```

## Log Context

### Request Context
```php
$context = [
    'request_id' => $request->getHeader('X-Request-ID')[0] ?? uniqid(),
    'method' => $request->getMethod(),
    'uri' => $request->getUri()->getPath(),
    'ip' => $request->getServerParams()['REMOTE_ADDR'],
    'user_agent' => $request->getHeader('User-Agent')[0] ?? null
];
```

### Error Context
```php
$context = [
    'exception' => [
        'class' => get_class($exception),
        'message' => $exception->getMessage(),
        'code' => $exception->getCode(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ]
];
```

## Usage Examples

### Logging Store Creation
```php
public function createStore(array $data): Store
{
    try {
        $store = $this->storeRepository->create($data);
        
        $this->logger->info('Store created', [
            'store_id' => $store->getId(),
            'name' => $store->getName()
        ]);
        
        return $store;
    } catch (\Exception $e) {
        $this->logger->error('Failed to create store', [
            'error' => $e->getMessage(),
            'data' => $data
        ]);
        
        throw $e;
    }
}
```

### Logging Authentication
```php
public function authenticate(string $token): bool
{
    try {
        $result = $this->validateToken($token);
        
        $this->logger->info('Authentication successful', [
            'token' => substr($token, 0, 10) . '...'
        ]);
        
        return $result;
    } catch (\Exception $e) {
        $this->logger->warning('Authentication failed', [
            'error' => $e->getMessage(),
            'token' => substr($token, 0, 10) . '...'
        ]);
        
        return false;
    }
}
```

## Log Rotation

### Configuration
```php
$handler = new RotatingFileHandler(
    __DIR__ . '/../../var/logs/app.log',
    7, // Keep 7 days of logs
    Logger::DEBUG
);
```

### Log Files
```
var/logs/
├── app.log
├── app-2024-03-20.log
├── app-2024-03-19.log
└── app-2024-03-18.log
```

## Best Practices

### Log Levels
- DEBUG: Detailed information
- INFO: General information
- WARNING: Potential issues
- ERROR: Error conditions
- CRITICAL: Critical conditions

### Context Data
- Include relevant IDs
- Add request information
- Include user context
- Add performance metrics

### Security
- Don't log sensitive data
- Sanitize log output
- Set proper permissions
- Implement log rotation

### Performance
- Use appropriate log levels
- Implement log buffering
- Configure log rotation
- Monitor log size 