<?php

use App\Config\ContainerInterface;
use App\Middleware\AuthMiddleware;
use FastRoute\Dispatcher;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use \Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range,Authorization');
header('Access-Control-Expose-Headers: Content-Length,Content-Range');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Max-Age: 1728000');
    header('Content-Type: text/plain charset=UTF-8');
    header('Content-Length: 0');
    exit(0);
}

// Set error handler
set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Set custom exception handler
set_exception_handler(function (Throwable $e) {
    error_log("Unhandled exception: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());

    $response = [
        'status' => 'error',
        'error' => [
            'code' => 'INTERNAL_ERROR',
            'message' => $e->getMessage(),
        ],
        'metadata' => [
            'timestamp' => date('c'),
            'request_id' => uniqid('req_', true)
        ]
    ];

    // Clear any previous output
    if (ob_get_level()) ob_end_clean();

    // Set response code and headers
    http_response_code(500);
    header('Content-Type: application/json; charset=UTF-8');

    // Calculate content length
    $json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    header('Content-Length: ' . strlen($json));

    echo $json;
    exit(1);
});

// Load .env file
try {
    $dotenv = \Dotenv\Dotenv::createImmutable('/var/www/html');
    $dotenv->safeLoad();
    error_log("Environment variables loaded: " . print_r($_ENV, true));
} catch (\Exception $e) {
    error_log("Error loading .env: " . $e->getMessage());
}

// Load configuration
$config = require __DIR__ . '/../config/auth.php';
/** @var ContainerInterface $container */
$container = require __DIR__ . '/../config/container.php';

// Create AuthMiddleware instance
$authMiddleware = new AuthMiddleware($config['api_token']);

// Load routes
$dispatcher = require __DIR__ . '/../config/routes.php';

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

// Dispatch route
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        header('HTTP/1.0 404 Not Found');
        echo json_encode([
            'status' => 'error',
            'error' => [
                'code' => 'NOT_FOUND',
                'message' => 'Route not found'
            ],
            'meta' => [
                'timestamp' => date('c'),
                'request_id' => uniqid()
            ]
        ]);
        break;
        
    case Dispatcher::METHOD_NOT_ALLOWED:
        header('HTTP/1.0 405 Method Not Allowed');
        echo json_encode([
            'status' => 'error',
            'error' => [
                'code' => 'METHOD_NOT_ALLOWED',
                'message' => 'Method not allowed'
            ],
            'meta' => [
                'timestamp' => date('c'),
                'request_id' => uniqid()
            ]
        ]);
        break;
        
    case Dispatcher::FOUND:
        try {
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];
            
            // Create request object
            $request = ServerRequest::fromGlobals();
            
            // Apply authentication for sensitive routes
            if (in_array($httpMethod, ['POST', 'PUT', 'DELETE'])) {
                $response = $authMiddleware->process($request, new class implements RequestHandlerInterface {
                    public function handle(\Psr\Http\Message\ServerRequestInterface $request): ResponseInterface
                    {
                        return new Response();
                    }
                });
                
                if ($response->getStatusCode() === 401) {
                    header('HTTP/1.0 401 Unauthorized');
                    echo json_encode([
                        'status' => 'error',
                        'error' => [
                            'code' => 'UNAUTHORIZED',
                            'message' => json_decode($response->getBody()->getContents(), true)['error']['message']
                        ],
                        'meta' => [
                            'timestamp' => date('c'),
                            'request_id' => uniqid()
                        ]
                    ]);
                    exit;
                }
            }
            
            // Create controller instance and call __invoke
            $controllerClass = $handler;
            $controller = $container->get($controllerClass);
            $response = $controller($request, $vars);
            
            // Send response
            http_response_code($response->getStatusCode());
            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    header(sprintf('%s: %s', $name, $value), false);
                }
            }
            echo $response->getBody()->getContents();
            exit;
        } catch (\Exception $e) {
            error_log('Unhandled exception in route handler: ' . $e->getMessage());
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'error' => [
                    'code' => 'INTERNAL_ERROR',
                    'message' => 'An internal server error occurred. Please try again later.',
                    'details' => $e->getMessage()
                ],
                'meta' => [
                    'timestamp' => date('c'),
                    'request_id' => uniqid()
                ]
            ]);
            exit;
        }
        break;
}
