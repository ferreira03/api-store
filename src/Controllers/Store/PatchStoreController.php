<?php

namespace App\Controllers\Store;

use App\Http\Response;
use App\Services\StoreService;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PatchStoreController
{
    public function __construct(
        private readonly StoreService $storeService
    ) {
    }

    public function __invoke(ServerRequestInterface $request, array $params = []): ResponseInterface
    {
        try {
            $id = (int) ($params['id'] ?? 0);
            if ($id <= 0) {
                return Response::error('INVALID_ID', 'Invalid store ID', [], 400);
            }

            $data = json_decode($request->getBody()->getContents(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return Response::error('INVALID_JSON', 'Invalid JSON payload', [], 400);
            }

            if (empty($data)) {
                return Response::error('INVALID_REQUEST', 'Request body cannot be empty', [], 400);
            }

            $store = $this->storeService->patchStore($id, $data);

            return Response::success($store->toArray());
        } catch (InvalidArgumentException $e) {
            return Response::error('VALIDATION_ERROR', $e->getMessage(), [], 400);
        } catch (\Exception $e) {
            return Response::error('INTERNAL_ERROR', $e->getMessage(), [], 500);
        }
    }
}
