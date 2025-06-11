<?php

namespace App\Controllers\Store;

class ListStoresController
{
    public function __invoke(array $params = []): array
    {
        // TODO: Implement store listing with filters
        return [
            'status' => 'success',
            'data' => [
                'items' => [],
                'pagination' => [
                    'total' => 0,
                    'per_page' => 10,
                    'current_page' => 1,
                    'last_page' => 1,
                ],
            ],
            'meta' => [
                'timestamp' => date('c'),
                'request_id' => uniqid(),
            ],
        ];
    }
}
