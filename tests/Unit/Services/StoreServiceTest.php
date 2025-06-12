<?php

namespace Tests\Unit\Services;

use App\Domain\Store;
use App\Exceptions\StoreValidationException;
use App\Repositories\interfaces\StoreRepositoryInterface;
use App\Services\StoreService;
use App\Services\StoreValidator;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class StoreServiceTest extends TestCase
{
    private StoreService $service;
    private StoreRepositoryInterface&MockObject $repository;
    private StoreValidator&MockObject $validator;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(StoreRepositoryInterface::class);
        $this->validator = $this->createMock(StoreValidator::class);
        $this->service = new StoreService($this->repository, $this->validator);
    }

    public function testGetStore(): void
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

        $this->repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($store);

        $result = $this->service->getStore(1);
        $this->assertSame($store, $result);
    }

    public function testGetStoreNotFound(): void
    {
        $this->repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Store with ID 1 not found');

        $this->service->getStore(1);
    }

    public function testListStores(): void
    {
        $stores = [
            new Store(
                null,
                'Store 1',
                '123 Main St',
                'City 1',
                'Country 1',
                '12345',
                '+1234567890',
                'store1@test.com'
            ),
            new Store(
                null,
                'Store 2',
                '456 Oak St',
                'City 2',
                'Country 2',
                '54321',
                '+9876543210',
                'store2@test.com'
            ),
        ];

        $filters = ['city' => 'City 1'];
        $sort = ['name' => 'asc'];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->with($filters, $sort)
            ->willReturn($stores);

        $result = $this->service->listStores($filters, $sort);
        $this->assertSame($stores, $result);
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

        $this->validator->expects($this->once())
            ->method('validateStoreData')
            ->with(
                'Test Store',
                '123 Main St',
                'Test City',
                'Test Country',
                '12345',
                '+1234567890',
                'test@store.com'
            );

        $this->repository->expects($this->once())
            ->method('save')
            ->willReturn($store);

        $result = $this->service->createStore(
            'Test Store',
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );

        $this->assertSame($store, $result);
    }

    public function testCreateStoreWithInvalidData(): void
    {
        $this->validator->expects($this->once())
            ->method('validateStoreData')
            ->willThrowException(new StoreValidationException('Store name is required'));

        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store name is required');

        $this->service->createStore(
            '',
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );
    }

    public function testUpdateStore(): void
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

        $updatedStore = new Store(
            null,
            'Updated Store',
            '456 New St',
            'New City',
            'New Country',
            '54321',
            '+9876543210',
            'new@store.com'
        );

        $this->repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($store);

        $this->validator->expects($this->once())
            ->method('validateStoreData')
            ->with(
                'Updated Store',
                '456 New St',
                'New City',
                'New Country',
                '54321',
                '+9876543210',
                'new@store.com'
            );

        $this->repository->expects($this->once())
            ->method('save')
            ->willReturn($updatedStore);

        $result = $this->service->updateStore(
            1,
            'Updated Store',
            '456 New St',
            'New City',
            'New Country',
            '54321',
            '+9876543210',
            'new@store.com',
            true
        );

        $this->assertSame($updatedStore, $result);
    }

    public function testUpdateStoreNotFound(): void
    {
        $this->repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Store with ID 1 not found');

        $this->service->updateStore(
            1,
            'Updated Store',
            '456 New St',
            'New City',
            'New Country',
            '54321',
            '+9876543210',
            'new@store.com',
            true
        );
    }

    public function testDeleteStore(): void
    {
        $this->repository->expects($this->once())
            ->method('exists')
            ->with(1)
            ->willReturn(true);

        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(true);

        $this->service->deleteStore(1);
    }

    public function testDeleteStoreNotFound(): void
    {
        $this->repository->expects($this->once())
            ->method('exists')
            ->with(1)
            ->willReturn(false);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Store with ID 1 not found');

        $this->service->deleteStore(1);
    }

    public function testPatchStore(): void
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

        $updatedStore = new Store(
            null,
            'Updated Store',
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );

        $this->repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($store);

        $this->validator->expects($this->once())
            ->method('validatePartialData')
            ->with(['name' => 'Updated Store']);

        $this->repository->expects($this->once())
            ->method('save')
            ->willReturn($updatedStore);

        $result = $this->service->patchStore(1, ['name' => 'Updated Store']);
        $this->assertSame($updatedStore, $result);
    }

    public function testValidateStoreDataWithLongName(): void
    {
        $this->validator->expects($this->once())
            ->method('validateStoreData')
            ->willThrowException(new StoreValidationException('Store name must not exceed 100 characters'));

        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store name must not exceed 100 characters');

        $this->service->createStore(
            str_repeat('a', 101),
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );
    }

    public function testValidateStoreDataWithLongAddress(): void
    {
        $this->validator->expects($this->once())
            ->method('validateStoreData')
            ->willThrowException(new StoreValidationException('Store address must not exceed 200 characters'));

        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store address must not exceed 200 characters');

        $this->service->createStore(
            'Test Store',
            str_repeat('a', 201),
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );
    }

    public function testValidateStoreDataWithLongCountry(): void
    {
        $this->validator->expects($this->once())
            ->method('validateStoreData')
            ->willThrowException(new StoreValidationException('Store country must not exceed 100 characters'));

        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store country must not exceed 100 characters');

        $this->service->createStore(
            'Test Store',
            '123 Main St',
            'Test City',
            str_repeat('a', 101),
            '12345',
            '+1234567890',
            'test@store.com'
        );
    }

    public function testValidateStoreDataWithLongPostalCode(): void
    {
        $this->validator->expects($this->once())
            ->method('validateStoreData')
            ->willThrowException(new StoreValidationException('Store postal code must not exceed 20 characters'));

        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store postal code must not exceed 20 characters');

        $this->service->createStore(
            'Test Store',
            '123 Main St',
            'Test City',
            'Test Country',
            str_repeat('a', 21),
            '+1234567890',
            'test@store.com'
        );
    }
}
