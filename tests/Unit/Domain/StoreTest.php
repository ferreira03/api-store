<?php

namespace Tests\Unit\Domain;

use App\Domain\Store;
use PHPUnit\Framework\TestCase;

class StoreTest extends TestCase
{
    private Store $store;

    protected function setUp(): void
    {
        $this->store = new Store(
            null,
            'Test Store',
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'test@store.com'
        );
    }

    public function testStoreCreation(): void
    {
        $this->assertEquals('Test Store', $this->store->getName());
        $this->assertEquals('123 Main St', $this->store->getAddress());
        $this->assertEquals('Test City', $this->store->getCity());
        $this->assertEquals('Test Country', $this->store->getCountry());
        $this->assertEquals('12345', $this->store->getPostalCode());
        $this->assertEquals('+1234567890', $this->store->getPhone());
        $this->assertEquals('test@store.com', $this->store->getEmail());
        $this->assertTrue($this->store->isActive());
        $this->assertNotNull($this->store->getCreatedAt());
        $this->assertNull($this->store->getUpdatedAt());
    }

    public function testInvalidEmailThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email format');

        new Store(
            null,
            'Test Store',
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            '+1234567890',
            'invalid-email'
        );
    }

    public function testInvalidPhoneThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid phone format');

        new Store(
            null,
            'Test Store',
            '123 Main St',
            'Test City',
            'Test Country',
            '12345',
            'invalid-phone',
            'test@store.com'
        );
    }

    public function testSetInvalidEmailThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email format');

        $this->store->setEmail('invalid-email');
    }

    public function testSetInvalidPhoneThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid phone format');

        $this->store->setPhone('invalid-phone');
    }

    public function testUpdateStoreProperties(): void
    {
        $this->store
            ->setName('Updated Store')
            ->setAddress('456 New St')
            ->setCity('New City')
            ->setCountry('New Country')
            ->setPostalCode('54321')
            ->setPhone('+9876543210')
            ->setEmail('new@store.com')
            ->setIsActive(false);

        $this->assertEquals('Updated Store', $this->store->getName());
        $this->assertEquals('456 New St', $this->store->getAddress());
        $this->assertEquals('New City', $this->store->getCity());
        $this->assertEquals('New Country', $this->store->getCountry());
        $this->assertEquals('54321', $this->store->getPostalCode());
        $this->assertEquals('+9876543210', $this->store->getPhone());
        $this->assertEquals('new@store.com', $this->store->getEmail());
        $this->assertFalse($this->store->isActive());
        $this->assertNotNull($this->store->getUpdatedAt());
    }

    public function testToArray(): void
    {
        $array = $this->store->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('Test Store', $array['name']);
        $this->assertEquals('123 Main St', $array['address']);
        $this->assertEquals('Test City', $array['city']);
        $this->assertEquals('Test Country', $array['country']);
        $this->assertEquals('12345', $array['postalCode']);
        $this->assertEquals('+1234567890', $array['phone']);
        $this->assertEquals('test@store.com', $array['email']);
        $this->assertTrue($array['isActive']);
        $this->assertIsString($array['createdAt']);
        $this->assertNull($array['updatedAt']);
    }
}
