<?php

namespace App\Controllers\Store;

use App\Http\Response;
use App\Services\StoreService;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UpdateStoreController
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

            $store = $this->storeService->updateStore(
                $id,
                $data['name'] ?? '',
                $data['address'] ?? '',
                $data['city'] ?? '',
                $data['country'] ?? '',
                $data['postal_code'] ?? '',
                $data['phone'] ?? '',
                $data['email'] ?? '',
                $data['is_active'] ?? true
            );

            return Response::success($store->toArray());
        } catch (InvalidArgumentException $e) {
            return Response::error('VALIDATION_ERROR', $e->getMessage(), [], 400);
        } catch (\Exception $e) {
            return Response::error('INTERNAL_ERROR', $e->getMessage(), [], 500);
        }
    }
}
