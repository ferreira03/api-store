<?php

namespace App\Services;

use App\Exceptions\StoreValidationException;

class StoreValidator
{
    private const MAX_NAME_LENGTH = 100;
    private const MAX_ADDRESS_LENGTH = 200;
    private const MAX_CITY_LENGTH = 100;
    private const MAX_COUNTRY_LENGTH = 100;
    private const MAX_POSTAL_CODE_LENGTH = 20;

    public function validateStoreData(
        string $name,
        string $address,
        string $city,
        string $country,
        string $postalCode,
        string $phone,
        string $email
    ): void {
        $this->validateName($name);
        $this->validateAddress($address);
        $this->validateCity($city);
        $this->validateCountry($country);
        $this->validatePostalCode($postalCode);
        $this->validatePhone($phone);
        $this->validateEmail($email);
    }

    public function validatePartialData(array $data): void
    {
        if (isset($data['name'])) {
            $this->validateName($data['name']);
        }

        if (isset($data['address'])) {
            $this->validateAddress($data['address']);
        }

        if (isset($data['city'])) {
            $this->validateCity($data['city']);
        }

        if (isset($data['country'])) {
            $this->validateCountry($data['country']);
        }

        if (isset($data['postal_code'])) {
            $this->validatePostalCode($data['postal_code']);
        }

        if (isset($data['phone'])) {
            $this->validatePhone($data['phone']);
        }

        if (isset($data['email'])) {
            $this->validateEmail($data['email']);
        }
    }

    private function validateName(string $name): void
    {
        if (empty($name)) {
            throw new StoreValidationException('Store name is required');
        }
        if (strlen($name) > self::MAX_NAME_LENGTH) {
            throw new StoreValidationException('Store name must not exceed ' . self::MAX_NAME_LENGTH . ' characters');
        }
    }

    private function validateAddress(string $address): void
    {
        if (empty($address)) {
            throw new StoreValidationException('Store address is required');
        }
        if (strlen($address) > self::MAX_ADDRESS_LENGTH) {
            throw new StoreValidationException('Store address must not exceed ' . self::MAX_ADDRESS_LENGTH . ' characters');
        }
    }

    private function validateCity(string $city): void
    {
        if (empty($city)) {
            throw new StoreValidationException('Store city is required');
        }
        if (strlen($city) > self::MAX_CITY_LENGTH) {
            throw new StoreValidationException('Store city must not exceed ' . self::MAX_CITY_LENGTH . ' characters');
        }
    }

    private function validateCountry(string $country): void
    {
        if (empty($country)) {
            throw new StoreValidationException('Store country is required');
        }
        if (strlen($country) > self::MAX_COUNTRY_LENGTH) {
            throw new StoreValidationException('Store country must not exceed ' . self::MAX_COUNTRY_LENGTH . ' characters');
        }
    }

    private function validatePostalCode(string $postalCode): void
    {
        if (empty($postalCode)) {
            throw new StoreValidationException('Store postal code is required');
        }
        if (strlen($postalCode) > self::MAX_POSTAL_CODE_LENGTH) {
            throw new StoreValidationException('Store postal code must not exceed ' . self::MAX_POSTAL_CODE_LENGTH . ' characters');
        }
    }

    private function validatePhone(string $phone): void
    {
        if (empty($phone)) {
            throw new StoreValidationException('Store phone is required');
        }
    }

    private function validateEmail(string $email): void
    {
        if (empty($email)) {
            throw new StoreValidationException('Store email is required');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new StoreValidationException('Invalid email format');
        }
    }
}
