<?php

namespace App\Controllers\Store;

use App\Http\Response;
use Psr\Http\Message\ResponseInterface;

class ListStoresController
{
    /**
     * @param array<string, mixed> $params
     */
    public function __invoke(array $params = []): ResponseInterface
    {
        // TODO: Implement store listing with filters
        return Response::success([
            'items' => [],
            'pagination' => [
                'total' => 0,
                'per_page' => 10,
                'current_page' => 1,
                'last_page' => 1,
            ],
        ]);
    }
}
