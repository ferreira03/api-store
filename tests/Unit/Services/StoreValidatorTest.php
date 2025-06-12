<?php

namespace Tests\Unit\Services;

use App\Exceptions\StoreValidationException;
use App\Services\StoreValidator;
use PHPUnit\Framework\TestCase;

class StoreValidatorTest extends TestCase
{
    private StoreValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new StoreValidator();
    }

    public function testValidateStoreDataWithValidData(): void
    {
        $this->validator->validateStoreData(
            'Test Store',
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );

        $this->assertTrue(true); // Se chegou aqui, não lançou exceção
    }

    public function testValidateStoreDataWithEmptyName(): void
    {
        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store name is required');

        $this->validator->validateStoreData(
            '',
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );
    }

    public function testValidateStoreDataWithLongName(): void
    {
        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store name must not exceed 100 characters');

        $this->validator->validateStoreData(
            str_repeat('a', 101),
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );
    }

    public function testValidateStoreDataWithEmptyAddress(): void
    {
        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store address is required');

        $this->validator->validateStoreData(
            'Test Store',
            '',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );
    }

    public function testValidateStoreDataWithLongAddress(): void
    {
        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store address must not exceed 200 characters');

        $this->validator->validateStoreData(
            'Test Store',
            str_repeat('a', 201),
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );
    }

    public function testValidateStoreDataWithEmptyCity(): void
    {
        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store city is required');

        $this->validator->validateStoreData(
            'Test Store',
            '123 Main St',
            '',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );
    }

    public function testValidateStoreDataWithLongCity(): void
    {
        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store city must not exceed 100 characters');

        $this->validator->validateStoreData(
            'Test Store',
            '123 Main St',
            str_repeat('a', 101),
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );
    }

    public function testValidateStoreDataWithEmptyCountry(): void
    {
        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store country is required');

        $this->validator->validateStoreData(
            'Test Store',
            '123 Main St',
            'Test City',
            '',
            '12345',
            '+1234567890',
            'test@store.com'
        );
    }

    public function testValidateStoreDataWithLongCountry(): void
    {
        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store country must not exceed 100 characters');

        $this->validator->validateStoreData(
            'Test Store',
            '123 Main St',
            'Test City',
            str_repeat('a', 101),
            '12345',
            '+1234567890',
            'test@store.com'
        );
    }

    public function testValidateStoreDataWithEmptyPostalCode(): void
    {
        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store postal code is required');

        $this->validator->validateStoreData(
            'Test Store',
            '123 Main St',
            'Test City',
            'Test Country',
            '',
            '+1234567890',
            'test@store.com'
        );
    }

    public function testValidateStoreDataWithLongPostalCode(): void
    {
        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store postal code must not exceed 20 characters');

        $this->validator->validateStoreData(
            'Test Store',
            '123 Main St',
            'Test City',
            'Test Country',
            str_repeat('a', 21),
            '+1234567890',
            'test@store.com'
        );
    }

    public function testValidateStoreDataWithEmptyPhone(): void
    {
        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store phone is required');

        $this->validator->validateStoreData(
            'Test Store',
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '',
            'test@store.com'
        );
    }

    public function testValidateStoreDataWithEmptyEmail(): void
    {
        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store email is required');

        $this->validator->validateStoreData(
            'Test Store',
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            ''
        );
    }

    public function testValidateStoreDataWithInvalidEmail(): void
    {
        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Invalid email format');

        $this->validator->validateStoreData(
            'Test Store',
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'invalid-email'
        );
    }

    public function testValidatePartialDataWithValidData(): void
    {
        $this->validator->validatePartialData([
            'name' => 'Test Store',
            'address' => '123 Main St',
            'city' => 'Test City',
            'country' => 'Test Country',
            'postal_code' => '12345',
            'phone' => '+1234567890',
            'email' => 'test@store.com',
        ]);

        $this->assertTrue(true); // Se chegou aqui, não lançou exceção
    }

    public function testValidatePartialDataWithEmptyName(): void
    {
        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store name is required');

        $this->validator->validatePartialData([
            'name' => '',
        ]);
    }

    public function testValidatePartialDataWithLongName(): void
    {
        $this->expectException(StoreValidationException::class);
        $this->expectExceptionMessage('Store name must not exceed 100 characters');

        $this->validator->validatePartialData([
            'name' => str_repeat('a', 101),
        ]);
    }
}
