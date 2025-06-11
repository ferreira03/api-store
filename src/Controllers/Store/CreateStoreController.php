<?php

namespace App\Controllers\Store;

class CreateStoreController
{
    public function __invoke(array $params = []): array
    {
        // TODO: Implement store creation
        return [
            'status' => 'success',
            'data' => null,
            'meta' => [
                'timestamp' => date('c'),
                'request_id' => uniqid(),
            ],
        ];
    }
}
