<?php

namespace App\Controllers\Store;

class UpdateStoreController
{
    public function __invoke(array $params): array
    {
        // TODO: Implement store update
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
