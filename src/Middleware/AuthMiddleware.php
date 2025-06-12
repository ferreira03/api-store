<?php

namespace App\Middleware;

use App\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware
{
    public function __construct(private string $apiToken)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authorization = $request->getHeaderLine('Authorization');

        if (empty($authorization)) {
            return Response::error('UNAUTHORIZED', 'Authentication required', [], 401);
        }

        if (!preg_match('/Bearer\s+(.*)$/i', $authorization, $matches)) {
            return Response::error('UNAUTHORIZED', 'Invalid authorization header format', [], 401);
        }

        $token = $matches[1];

        if ($token !== $this->apiToken) {
            return Response::error('UNAUTHORIZED', 'Invalid token', [], 401);
        }

        return $handler->handle($request);
    }
}
