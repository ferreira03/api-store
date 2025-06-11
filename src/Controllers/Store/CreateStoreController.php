<?php

namespace App\Controllers\Store;

use App\Http\Response;
use Psr\Http\Message\ResponseInterface;

class CreateStoreController
{
    /**
     * @param array<string, mixed> $params
     */
    public function __invoke(array $params = []): ResponseInterface
    {
        // TODO: Implement store creation
        return Response::success(null);
    }
}
