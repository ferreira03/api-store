<?php

namespace App\Services;

use App\Domain\Store;
use App\Repositories\interfaces\StoreRepositoryInterface;
use InvalidArgumentException;

class StoreService
{
    public function __construct(
        private StoreRepositoryInterface $repository,
        private StoreValidator $validator
    ) {
    }

    /**
     * Get a store by ID
     *
     * @throws InvalidArgumentException if store not found
     */
    public function getStore(int $id): Store
    {
        $store = $this->repository->findById($id);
        if ($store === null) {
            throw new InvalidArgumentException("Store with ID $id not found");
        }

        return $store;
    }

    /**
     * List all stores with optional filtering and sorting
     *
     * @param array $filters Associative array of filters
     * @param array $sort Associative array of sort fields and directions
     * @return Store[]
     */
    public function listStores(array $filters = [], array $sort = []): array
    {
        return $this->repository->findAll($filters, $sort);
    }

    /**
     * Create a new store
     *
     * @throws InvalidArgumentException if store data is invalid
     */
    public function createStore(
        string $name,
        string $address,
        string $city,
        string $country,
        string $postalCode,
        string $phone,
        string $email,
        bool $isActive = true
    ): Store {
        $this->validator->validateStoreData($name, $address, $city, $country, $postalCode, $phone, $email);

        $store = new Store(
            null,
            $name,
            $address,
            $city,
            $country,
            $postalCode,
            $phone,
            $email,
            $isActive
        );

        return $this->repository->save($store);
    }

    /**
     * Update an existing store
     *
     * @throws InvalidArgumentException if store not found or data is invalid
     */
    public function updateStore(
        int $id,
        string $name,
        string $address,
        string $city,
        string $country,
        string $postalCode,
        string $phone,
        string $email,
        bool $isActive
    ): Store {
        $store = $this->getStore($id);
        $this->validator->validateStoreData($name, $address, $city, $country, $postalCode, $phone, $email);

        $store->setName($name)
            ->setAddress($address)
            ->setCity($city)
            ->setCountry($country)
            ->setPostalCode($postalCode)
            ->setPhone($phone)
            ->setEmail($email)
            ->setIsActive($isActive);

        return $this->repository->save($store);
    }

    /**
     * Update an existing store partially
     *
     * @throws InvalidArgumentException if store not found or data is invalid
     */
    public function patchStore(int $id, array $data): Store
    {
        $store = $this->getStore($id);
        $this->validator->validatePartialData($data);

        $this->updateStoreFields($store, $data);

        return $this->repository->save($store);
    }

    /**
     * Delete a store
     *
     * @throws InvalidArgumentException if store not found
     */
    public function deleteStore(int $id): void
    {
        if (!$this->repository->exists($id)) {
            throw new InvalidArgumentException("Store with ID $id not found");
        }

        $this->repository->delete($id);
    }

    /**
     * Update store fields based on provided data
     */
    private function updateStoreFields(Store $store, array $data): void
    {
        if (isset($data['name'])) {
            $store->setName($data['name']);
        }

        if (isset($data['address'])) {
            $store->setAddress($data['address']);
        }

        if (isset($data['city'])) {
            $store->setCity($data['city']);
        }

        if (isset($data['country'])) {
            $store->setCountry($data['country']);
        }

        if (isset($data['postal_code'])) {
            $store->setPostalCode($data['postal_code']);
        }

        if (isset($data['phone'])) {
            $store->setPhone($data['phone']);
        }

        if (isset($data['email'])) {
            $store->setEmail($data['email']);
        }

        if (isset($data['is_active'])) {
            $store->setIsActive($data['is_active']);
        }
    }
}
