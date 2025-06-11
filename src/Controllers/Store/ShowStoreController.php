<?php

namespace App\Controllers\Store;

class ShowStoreController
{
    public function __invoke(array $params): array
    {
        // TODO: Implement store retrieval
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
