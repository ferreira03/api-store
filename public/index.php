<?php

use App\Middleware\AuthMiddleware;
use FastRoute\Dispatcher;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

require_once __DIR__ . '/../vendor/autoload.php';

// Load configuration
$config = require __DIR__ . '/../config/auth.php';

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
        $controller = new $handler();
        $response = $controller($vars);
        
        // Send response
        header('Content-Type: application/json');
        echo json_encode($response);
        break;
}
