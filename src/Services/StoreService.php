<?php

namespace App\Services;

use App\Domain\Store;
use App\Exceptions\StoreValidationException;
use App\Repositories\interfaces\StoreRepositoryInterface;
use InvalidArgumentException;

class StoreService
{
    public function __construct(
        private StoreRepositoryInterface $repository
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
        error_log('StoreService: Starting store creation');
        error_log('StoreService: Parameters received - name: ' . $name . ', address: ' . $address . ', city: ' . $city . ', country: ' . $country . ', postalCode: ' . $postalCode . ', phone: ' . $phone . ', email: ' . $email . ', isActive: ' . ($isActive ? 'true' : 'false'));

        $this->validateStoreData($name, $address, $city, $country, $postalCode, $phone, $email);
        error_log('StoreService: Data validated successfully');

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
        error_log('StoreService: Store object created');

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
        $this->validateStoreData($name, $address, $city, $country, $postalCode, $phone, $email);

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

        // Validate and update only the fields that were provided
        if (isset($data['name'])) {
            if (empty($data['name'])) {
                throw new StoreValidationException('Store name is required');
            }
            if (strlen($data['name']) > 100) {
                throw new StoreValidationException('Store name must not exceed 100 characters');
            }
            $store->setName($data['name']);
        }

        if (isset($data['address'])) {
            if (empty($data['address'])) {
                throw new StoreValidationException('Store address is required');
            }
            if (strlen($data['address']) > 200) {
                throw new StoreValidationException('Store address must not exceed 200 characters');
            }
            $store->setAddress($data['address']);
        }

        if (isset($data['city'])) {
            if (empty($data['city'])) {
                throw new StoreValidationException('Store city is required');
            }
            if (strlen($data['city']) > 100) {
                throw new StoreValidationException('Store city must not exceed 100 characters');
            }
            $store->setCity($data['city']);
        }

        if (isset($data['country'])) {
            if (empty($data['country'])) {
                throw new StoreValidationException('Store country is required');
            }
            if (strlen($data['country']) > 100) {
                throw new StoreValidationException('Store country must not exceed 100 characters');
            }
            $store->setCountry($data['country']);
        }

        if (isset($data['postal_code'])) {
            if (empty($data['postal_code'])) {
                throw new StoreValidationException('Store postal code is required');
            }
            if (strlen($data['postal_code']) > 20) {
                throw new StoreValidationException('Store postal code must not exceed 20 characters');
            }
            $store->setPostalCode($data['postal_code']);
        }

        if (isset($data['phone'])) {
            if (empty($data['phone'])) {
                throw new StoreValidationException('Store phone is required');
            }
            $store->setPhone($data['phone']);
        }

        if (isset($data['email'])) {
            if (empty($data['email'])) {
                throw new StoreValidationException('Store email is required');
            }
            $store->setEmail($data['email']);
        }

        if (isset($data['is_active'])) {
            $store->setIsActive($data['is_active']);
        }

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
     * Validate store data
     *
     * @throws InvalidArgumentException if data is invalid
     */
    private function validateStoreData(
        string $name,
        string $address,
        string $city,
        string $country,
        string $postalCode,
        string $phone,
        string $email
    ): void {
        error_log('StoreService: Starting data validation');

        if (empty($name)) {
            error_log('StoreService: Name is empty');

            throw new StoreValidationException('Store name is required');
        }

        if (empty($address)) {
            error_log('StoreService: Address is empty');

            throw new StoreValidationException('Store address is required');
        }

        if (empty($city)) {
            error_log('StoreService: City is empty');

            throw new StoreValidationException('Store city is required');
        }

        if (empty($country)) {
            error_log('StoreService: Country is empty');

            throw new StoreValidationException('Store country is required');
        }

        if (empty($postalCode)) {
            error_log('StoreService: Postal code is empty');

            throw new StoreValidationException('Store postal code is required');
        }

        if (empty($phone)) {
            error_log('StoreService: Phone is empty');

            throw new StoreValidationException('Store phone is required');
        }

        if (empty($email)) {
            error_log('StoreService: Email is empty');

            throw new StoreValidationException('Store email is required');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            error_log('StoreService: Invalid email format');

            throw new StoreValidationException('Invalid email format');
        }

        if (strlen($name) > 100) {
            throw new StoreValidationException('Store name must not exceed 100 characters');
        }

        if (strlen($address) > 200) {
            throw new StoreValidationException('Store address must not exceed 200 characters');
        }

        if (strlen($city) > 100) {
            throw new StoreValidationException('Store city must not exceed 100 characters');
        }

        if (strlen($country) > 100) {
            throw new StoreValidationException('Store country must not exceed 100 characters');
        }

        if (strlen($postalCode) > 20) {
            throw new StoreValidationException('Store postal code must not exceed 20 characters');
        }

        error_log('StoreService: Data validation completed successfully');
    }
}
