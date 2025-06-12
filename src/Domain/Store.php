<?php

namespace App\Domain;

/**
 * Store Entity
 *
 * Represents a store in the system with its basic properties and business rules.
 */
class Store
{
    private ?int $id;
    private string $name;
    private string $address;
    private string $city;
    private string $country;
    private string $postalCode;
    private string $phone;
    private string $email;
    private bool $isActive;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    /**
     * Store constructor
     */
    public function __construct(
        ?int $id,
        string $name,
        string $address,
        string $city,
        string $country,
        string $postalCode,
        string $phone,
        string $email,
        bool $isActive = true,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->city = $city;
        $this->country = $country;
        $this->postalCode = $postalCode;
        $this->phone = $phone;
        $this->email = $email;
        $this->isActive = $isActive;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt;

        $this->validateEmail($email);
        $this->validatePhone($phone);
    }

    /**
     * Validate email format
     *
     * @throws \InvalidArgumentException
     */
    private function validateEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }
    }

    /**
     * Validate phone format
     *
     * @throws \InvalidArgumentException
     */
    private function validatePhone(string $phone): void
    {
        if (!preg_match('/^\+?[1-9]\d{1,14}$/', $phone)) {
            throw new \InvalidArgumentException('Invalid phone format');
        }
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    // Setters
    public function setName(string $name): self
    {
        $this->name = $name;
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function setPhone(string $phone): self
    {
        $this->validatePhone($phone);
        $this->phone = $phone;
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->validateEmail($email);
        $this->email = $email;
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * Convert store to array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'postalCode' => $this->postalCode,
            'phone' => $this->phone,
            'email' => $this->email,
            'isActive' => $this->isActive,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
