<?php

namespace App\Controllers\Store;

use App\Http\Response;
use App\Services\StoreService;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ListStoresController
{
    public function __construct(
        private readonly StoreService $storeService
    ) {
    }

    public function __invoke(ServerRequestInterface $request, array $params = []): ResponseInterface
    {
        try {
            $queryParams = $request->getQueryParams();

            $filters = [];
            if (isset($queryParams['city'])) {
                $filters['city'] = $queryParams['city'];
            }
            if (isset($queryParams['country'])) {
                $filters['country'] = $queryParams['country'];
            }
            if (isset($queryParams['is_active'])) {
                $filters['is_active'] = filter_var($queryParams['is_active'], FILTER_VALIDATE_BOOLEAN);
            }

            $sort = [];
            if (isset($queryParams['sort'])) {
                $sortField = $queryParams['sort'];
                $sortDirection = $queryParams['direction'] ?? 'asc';
                $sort[$sortField] = $sortDirection;
            }

            $stores = $this->storeService->listStores($filters, $sort);

            return Response::success([
                'items' => array_map(fn ($store) => $store->toArray(), $stores),
                'pagination' => [
                    'total' => count($stores),
                    'per_page' => count($stores),
                    'current_page' => 1,
                    'last_page' => 1,
                ],
            ]);
        } catch (InvalidArgumentException $e) {
            return Response::error('VALIDATION_ERROR', $e->getMessage(), [], 400);
        } catch (\Exception $e) {
            return Response::error('INTERNAL_ERROR', $e->getMessage(), [], 500);
        }
    }
}
