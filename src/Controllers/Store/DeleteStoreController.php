<?php

namespace App\Controllers\Store;

class DeleteStoreController
{
    public function __invoke(array $params): array
    {
        // TODO: Implement store deletion
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
