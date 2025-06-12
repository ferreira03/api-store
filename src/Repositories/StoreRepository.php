<?php

namespace App\Repositories;

use App\Domain\Store;
use App\Exceptions\StoreNotFoundException;
use App\Exceptions\StoreSaveException;
use App\Repositories\interfaces\StoreRepositoryInterface;
use DateTimeImmutable;
use PDO;

class StoreRepository implements StoreRepositoryInterface
{
    private const TABLE_NAME = 'stores';
    private const DEFAULT_SORT = 'ASC';
    private const ALLOWED_FIELDS = [
        'id', 'name', 'address', 'city', 'country',
        'postal_code', 'phone', 'email', 'is_active',
        'created_at', 'updated_at',
    ];

    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @throws StoreNotFoundException
     */
    public function findById(?int $id): Store
    {
        if ($id === null) {
            throw new StoreNotFoundException('Store ID cannot be null');
        }

        $stmt = $this->db->prepare(sprintf('SELECT * FROM %s WHERE id = :id', self::TABLE_NAME));
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            throw new StoreNotFoundException("Store with ID {$id} not found");
        }

        return $this->createStoreFromData($data);
    }

    /**
     * @param array<string, mixed> $filters
     * @param array<string, string> $sort
     * @return array<Store>
     */
    public function findAll(array $filters = [], array $sort = []): array
    {
        $sql = sprintf('SELECT * FROM %s', self::TABLE_NAME);
        $params = [];

        if (!empty($filters)) {
            $whereConditions = [];
            foreach ($filters as $field => $value) {
                if (!in_array($field, self::ALLOWED_FIELDS)) {
                    throw new \InvalidArgumentException("Invalid filter field: {$field}");
                }
                $whereConditions[] = "$field = :$field";
                $params[$field] = $value;
            }
            $sql .= ' WHERE ' . implode(' AND ', $whereConditions);
        }

        if (!empty($sort)) {
            $orderBy = [];
            foreach ($sort as $field => $direction) {
                if (!in_array($field, self::ALLOWED_FIELDS)) {
                    throw new \InvalidArgumentException("Invalid sort field: {$field}");
                }
                $direction = strtoupper($direction) === 'DESC' ? 'DESC' : self::DEFAULT_SORT;
                $orderBy[] = "$field $direction";
            }
            $sql .= ' ORDER BY ' . implode(', ', $orderBy);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'createStoreFromData'], $results);
    }

    /**
     * @throws StoreSaveException
     */
    public function save(Store $store): Store
    {
        error_log('StoreRepository: Starting save process');
        error_log('StoreRepository: Store data: ' . print_r($store->toArray(), true));

        try {
            if ($store->getId() === null) {
                return $this->create($store);
            }

            return $this->update($store);
        } catch (\PDOException $e) {
            error_log('StoreRepository: Database error: ' . $e->getMessage());

            throw new \RuntimeException('Failed to save store: ' . $e->getMessage());
        }
    }

    /**
     * Create a new store
     *
     * @throws \RuntimeException if store creation fails
     */
    private function create(Store $store): Store
    {
        error_log('StoreRepository: Creating new store');
        $sql = 'INSERT INTO stores (name, address, city, country, postal_code, phone, email, is_active, created_at) 
                VALUES (:name, :address, :city, :country, :postal_code, :phone, :email, :is_active, :created_at)';
        $stmt = $this->db->prepare($sql);
        error_log('StoreRepository: SQL prepared: ' . $sql);

        $stmt->bindValue(':name', $store->getName());
        $stmt->bindValue(':address', $store->getAddress());
        $stmt->bindValue(':city', $store->getCity());
        $stmt->bindValue(':country', $store->getCountry());
        $stmt->bindValue(':postal_code', $store->getPostalCode());
        $stmt->bindValue(':phone', $store->getPhone());
        $stmt->bindValue(':email', $store->getEmail());
        $stmt->bindValue(':is_active', $store->isActive(), \PDO::PARAM_BOOL);
        $stmt->bindValue(':created_at', $store->getCreatedAt()->format('Y-m-d H:i:s'));
        error_log('StoreRepository: Parameters bound');

        $stmt->execute();
        error_log('StoreRepository: SQL executed');

        $lastId = (int) $this->db->lastInsertId();
        error_log('StoreRepository: Last insert ID: ' . $lastId);

        return $this->findById($lastId);
    }

    /**
     * Update an existing store
     *
     * @throws \RuntimeException if store update fails
     */
    private function update(Store $store): Store
    {
        error_log('StoreRepository: Updating existing store');
        $sql = 'UPDATE stores 
                SET name = :name, 
                    address = :address, 
                    city = :city, 
                    country = :country, 
                    postal_code = :postal_code, 
                    phone = :phone, 
                    email = :email, 
                    is_active = :is_active, 
                    updated_at = :updated_at 
                WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        error_log('StoreRepository: SQL prepared: ' . $sql);

        $stmt->bindValue(':id', $store->getId());
        $stmt->bindValue(':name', $store->getName());
        $stmt->bindValue(':address', $store->getAddress());
        $stmt->bindValue(':city', $store->getCity());
        $stmt->bindValue(':country', $store->getCountry());
        $stmt->bindValue(':postal_code', $store->getPostalCode());
        $stmt->bindValue(':phone', $store->getPhone());
        $stmt->bindValue(':email', $store->getEmail());
        $stmt->bindValue(':is_active', $store->isActive(), \PDO::PARAM_BOOL);
        $stmt->bindValue(':updated_at', (new \DateTime())->format('Y-m-d H:i:s'));
        error_log('StoreRepository: Parameters bound');

        $stmt->execute();
        error_log('StoreRepository: SQL executed');

        return $this->findById($store->getId());
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(sprintf('DELETE FROM %s WHERE id = :id', self::TABLE_NAME));
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }

    public function exists(int $id): bool
    {
        $stmt = $this->db->prepare(sprintf('SELECT COUNT(*) FROM %s WHERE id = :id', self::TABLE_NAME));
        $stmt->execute(['id' => $id]);

        return (bool) $stmt->fetchColumn();
    }

    /**
     * Create a Store instance from database data
     * @param array<string, mixed> $data
     */
    private function createStoreFromData(array $data): Store
    {
        error_log('StoreRepository: Creating store from data: ' . print_r($data, true));

        $store = new Store(
            $data['id'] ?? null,
            $data['name'],
            $data['address'],
            $data['city'],
            $data['country'],
            $data['postal_code'],
            $data['phone'],
            $data['email'],
            (bool) $data['is_active'],
            isset($data['created_at']) ? new DateTimeImmutable($data['created_at']) : null,
            isset($data['updated_at']) ? new DateTimeImmutable($data['updated_at']) : null
        );

        error_log('StoreRepository: Store object created');

        return $store;
    }
}
