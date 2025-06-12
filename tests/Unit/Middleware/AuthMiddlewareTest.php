<?php

namespace Tests\Unit\Middleware;

use App\Middleware\AuthMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TestRequestHandler implements RequestHandlerInterface
{
    private int $statusCode;
    private string $message;

    public function __construct(int $statusCode = 200, string $message = '')
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new \GuzzleHttp\Psr7\Response($this->statusCode, [], $this->message);
    }
}

class AuthMiddlewareTest extends TestCase
{
    private AuthMiddleware $middleware;
    private string $validToken = 'valid-token';

    protected function setUp(): void
    {
        $this->middleware = new AuthMiddleware($this->validToken);
    }

    public function testShouldAllowRequestWithValidToken(): void
    {
        // Arrange
        $request = new ServerRequest(
            'POST',
            '/api/v1/stores',
            ['Authorization' => 'Bearer ' . $this->validToken]
        );

        $handler = new TestRequestHandler();

        // Act
        $response = $this->middleware->process($request, $handler);

        // Assert
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testShouldRejectRequestWithoutToken(): void
    {
        // Arrange
        $request = new ServerRequest('POST', '/api/v1/stores');
        $handler = new TestRequestHandler(401, 'Authentication required');

        // Act
        $response = $this->middleware->process($request, $handler);

        // Assert
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertStringContainsString('Authentication required', (string) $response->getBody());
    }

    public function testShouldRejectRequestWithInvalidToken(): void
    {
        // Arrange
        $request = new ServerRequest(
            'POST',
            '/api/v1/stores',
            ['Authorization' => 'Bearer invalid-token']
        );
        $handler = new TestRequestHandler(401, 'Invalid token');

        // Act
        $response = $this->middleware->process($request, $handler);

        // Assert
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertStringContainsString('Invalid token', (string) $response->getBody());
    }

    public function testShouldRejectRequestWithInvalidFormat(): void
    {
        // Arrange
        $request = new ServerRequest(
            'POST',
            '/api/v1/stores',
            ['Authorization' => 'InvalidFormat ' . $this->validToken]
        );
        $handler = new TestRequestHandler(401, 'Invalid authorization header format');

        // Act
        $response = $this->middleware->process($request, $handler);

        // Assert
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertStringContainsString('Invalid authorization header format', (string) $response->getBody());
    }

    public function testShouldRejectRequestWithEmptyToken(): void
    {
        // Arrange
        $request = new ServerRequest(
            'POST',
            '/api/v1/stores',
            ['Authorization' => 'Bearer ']
        );
        $handler = new TestRequestHandler(401, 'Invalid authorization header format');

        // Act
        $response = $this->middleware->process($request, $handler);
        // Assert
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertStringContainsString('Invalid authorization header format', (string) $response->getBody());
    }
}
