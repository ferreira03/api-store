<?php

namespace Tests\Unit\Repositories;

use App\Database\Migrations\CreateStoresTable;
use App\Domain\Store;
use App\Exceptions\StoreNotFoundException;
use App\Repositories\StoreRepository;
use PDO;
use PHPUnit\Framework\TestCase;

class StoreRepositoryTest extends TestCase
{
    private PDO $db;
    private StoreRepository $repository;

    protected function setUp(): void
    {
        // Create MySQL connection for testing
        $host = getenv('DB_HOST') ?: 'mysql';
        $dbname = getenv('DB_NAME') ?: 'store_db';
        $user = getenv('DB_USER') ?: 'store_user';
        $pass = getenv('DB_PASS') ?: 'root';

        $this->db = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );

        // Run migrations
        $migration = new CreateStoresTable($this->db);
        $migration->up();

        $this->repository = new StoreRepository($this->db);
    }

    protected function tearDown(): void
    {
        $this->db->exec('TRUNCATE TABLE stores');
    }

    public function testSaveAndFindById(): void
    {
        $store = new Store(
            null,
            'Test Store',
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );

        $savedStore = $this->repository->save($store);
        $this->assertNotNull($savedStore->getId());

        $foundStore = $this->repository->findById($savedStore->getId());
        $this->assertNotNull($foundStore);
        $this->assertEquals($savedStore->getName(), $foundStore->getName());
        $this->assertEquals($savedStore->getAddress(), $foundStore->getAddress());
        $this->assertEquals($savedStore->getCity(), $foundStore->getCity());
        $this->assertEquals($savedStore->getCountry(), $foundStore->getCountry());
        $this->assertEquals($savedStore->getPostalCode(), $foundStore->getPostalCode());
        $this->assertEquals($savedStore->getPhone(), $foundStore->getPhone());
        $this->assertEquals($savedStore->getEmail(), $foundStore->getEmail());
        $this->assertEquals($savedStore->isActive(), $foundStore->isActive());
    }

    public function testFindByIdWithInvalidId(): void
    {
        $this->expectException(StoreNotFoundException::class);
        $this->expectExceptionMessage('Store with ID 999 not found');
        $this->repository->findById(999);
    }

    public function testFindAllWithEmptyResult(): void
    {
        $results = $this->repository->findAll();
        $this->assertEmpty($results);
    }

    public function testFindAllWithMultipleStores(): void
    {
        // Create test stores
        $store1 = new Store(
            null,
            'Store 1',
            '123 Main St',
            'City 1',
            'Country 1',
            '12345',
            '+1234567890',
            'store1@test.com'
        );
        $store2 = new Store(
            null,
            'Store 2',
            '456 Oak St',
            'City 2',
            'Country 2',
            '54321',
            '+9876543210',
            'store2@test.com'
        );

        $this->repository->save($store1);
        $this->repository->save($store2);

        $results = $this->repository->findAll();
        $this->assertCount(2, $results);
        $this->assertEquals('Store 1', $results[0]->getName());
        $this->assertEquals('Store 2', $results[1]->getName());
    }

    public function testFindAllWithFilters(): void
    {
        // Create test stores
        $store1 = new Store(
            null,
            'Store 1',
            '123 Main St',
            'City 1',
            'Country 1',
            '12345',
            '+1234567890',
            'store1@test.com'
        );
        $store2 = new Store(
            null,
            'Store 2',
            '456 Oak St',
            'City 2',
            'Country 2',
            '54321',
            '+9876543210',
            'store2@test.com'
        );

        $this->repository->save($store1);
        $this->repository->save($store2);

        // Test filtering by city
        $results = $this->repository->findAll(['city' => 'City 1']);
        $this->assertCount(1, $results);
        $this->assertEquals('Store 1', $results[0]->getName());

        // Test filtering by country
        $results = $this->repository->findAll(['country' => 'Country 2']);
        $this->assertCount(1, $results);
        $this->assertEquals('Store 2', $results[0]->getName());

        // Test filtering by multiple fields
        $results = $this->repository->findAll([
            'city' => 'City 1',
            'country' => 'Country 1',
        ]);
        $this->assertCount(1, $results);
        $this->assertEquals('Store 1', $results[0]->getName());
    }

    public function testFindAllWithSorting(): void
    {
        // Create test stores
        $store1 = new Store(
            null,
            'Store A',
            '123 Main St',
            'City 1',
            'Country 1',
            '12345',
            '+1234567890',
            'store1@test.com'
        );
        $store2 = new Store(
            null,
            'Store B',
            '456 Oak St',
            'City 2',
            'Country 2',
            '54321',
            '+9876543210',
            'store2@test.com'
        );

        $this->repository->save($store1);
        $this->repository->save($store2);

        // Test ascending sort
        $results = $this->repository->findAll([], ['name' => 'asc']);
        $this->assertCount(2, $results);
        $this->assertEquals('Store A', $results[0]->getName());
        $this->assertEquals('Store B', $results[1]->getName());

        // Test descending sort
        $results = $this->repository->findAll([], ['name' => 'desc']);
        $this->assertCount(2, $results);
        $this->assertEquals('Store B', $results[0]->getName());
        $this->assertEquals('Store A', $results[1]->getName());
    }

    public function testCreateStore(): void
    {
        $store = new Store(
            null,
            'Test Store',
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );

        $savedStore = $this->repository->save($store);
        $this->assertNotNull($savedStore->getId());
        $this->assertEquals('Test Store', $savedStore->getName());
        $this->assertEquals('123 Main St', $savedStore->getAddress());
        $this->assertEquals('Test City', $savedStore->getCity());
        $this->assertEquals('Test Country', $savedStore->getCountry());
        $this->assertEquals('12345', $savedStore->getPostalCode());
        $this->assertEquals('+1234567890', $savedStore->getPhone());
        $this->assertEquals('test@store.com', $savedStore->getEmail());
        $this->assertTrue($savedStore->isActive());
        $this->assertNotNull($savedStore->getCreatedAt());
        $this->assertNull($savedStore->getUpdatedAt());
    }

    public function testUpdateStore(): void
    {
        // First create a store
        $store = new Store(
            null,
            'Test Store',
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );

        $savedStore = $this->repository->save($store);
        $storeId = $savedStore->getId();
        $this->assertNotNull($storeId);

        // Then update it
        $savedStore->setName('Updated Store')
            ->setAddress('456 New St')
            ->setCity('New City')
            ->setCountry('New Country')
            ->setPostalCode('54321')
            ->setPhone('+9876543210')
            ->setEmail('new@store.com')
            ->setIsActive(false);

        $updatedStore = $this->repository->save($savedStore);
        $this->assertEquals($storeId, $updatedStore->getId());
        $this->assertEquals('Updated Store', $updatedStore->getName());
        $this->assertEquals('456 New St', $updatedStore->getAddress());
        $this->assertEquals('New City', $updatedStore->getCity());
        $this->assertEquals('New Country', $updatedStore->getCountry());
        $this->assertEquals('54321', $updatedStore->getPostalCode());
        $this->assertEquals('+9876543210', $updatedStore->getPhone());
        $this->assertEquals('new@store.com', $updatedStore->getEmail());
        $this->assertFalse($updatedStore->isActive());
        $this->assertNotNull($updatedStore->getUpdatedAt());
    }

    public function testDelete(): void
    {
        $store = new Store(
            null,
            'Test Store',
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );

        $savedStore = $this->repository->save($store);
        $storeId = $savedStore->getId();
        $this->assertNotNull($storeId);

        $this->assertTrue($this->repository->delete($storeId));
        $this->expectException(StoreNotFoundException::class);
        $this->repository->findById($storeId);
    }

    public function testDeleteNonExistentStore(): void
    {
        $this->assertFalse($this->repository->delete(999));
    }

    public function testExists(): void
    {
        $store = new Store(
            null,
            'Test Store',
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );

        $savedStore = $this->repository->save($store);
        $storeId = $savedStore->getId();
        $this->assertNotNull($storeId);

        $this->assertTrue($this->repository->exists($storeId));
        $this->assertFalse($this->repository->exists(999));
    }
}
