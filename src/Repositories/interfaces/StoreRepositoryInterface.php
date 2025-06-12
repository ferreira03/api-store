<?php

namespace App\Repositories\interfaces;

use App\Domain\Store;

interface StoreRepositoryInterface
{
    /**
     * Find a store by its ID
     */
    public function findById(int $id): ?Store;

    /**
     * Find all stores with optional filtering and sorting
     *
     * @param array $filters Associative array of filters
     * @param array $sort Associative array of sort fields and directions
     * @return Store[]
     */
    public function findAll(array $filters = [], array $sort = []): array;

    /**
     * Save a store (create or update)
     */
    public function save(Store $store): Store;

    /**
     * Delete a store by its ID
     */
    public function delete(int $id): bool;

    /**
     * Check if a store exists by its ID
     */
    public function exists(int $id): bool;
}
