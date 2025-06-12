<?php

namespace App\Database\Migrations;

use PDO;

class InsertStoresData
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function up(): void
    {
        $stores = [
            [
                'name' => 'Tech Store São Paulo',
                'address' => 'Av. Paulista, 1000',
                'city' => 'São Paulo',
                'country' => 'Brazil',
                'postal_code' => '01310-100',
                'phone' => '+5511999999999',
                'email' => 'sp@techstore.com',
                'is_active' => 1,
                'created_at' => '2024-01-01 10:00:00',
            ],
            [
                'name' => 'Tech Store Rio de Janeiro',
                'address' => 'Av. Rio Branco, 156',
                'city' => 'Rio de Janeiro',
                'country' => 'Brazil',
                'postal_code' => '20040-007',
                'phone' => '+5521999999999',
                'email' => 'rj@techstore.com',
                'is_active' => 1,
                'created_at' => '2024-01-02 10:00:00',
            ],
            [
                'name' => 'Tech Store New York',
                'address' => '5th Avenue, 123',
                'city' => 'New York',
                'country' => 'United States',
                'postal_code' => '10001',
                'phone' => '+12125551234',
                'email' => 'ny@techstore.com',
                'is_active' => 1,
                'created_at' => '2024-01-03 10:00:00',
            ],
            [
                'name' => 'Tech Store London',
                'address' => 'Oxford Street, 45',
                'city' => 'London',
                'country' => 'United Kingdom',
                'postal_code' => 'W1D 2DZ',
                'phone' => '+44123456789',
                'email' => 'london@techstore.com',
                'is_active' => 0,
                'created_at' => '2024-01-04 10:00:00',
            ],
            [
                'name' => 'Tech Store Paris',
                'address' => 'Champs-Élysées, 78',
                'city' => 'Paris',
                'country' => 'France',
                'postal_code' => '75008',
                'phone' => '+33123456789',
                'email' => 'paris@techstore.com',
                'is_active' => 1,
                'created_at' => '2024-01-05 10:00:00',
            ],
            [
                'name' => 'Tech Store Tokyo',
                'address' => 'Shibuya Crossing, 1-1',
                'city' => 'Tokyo',
                'country' => 'Japan',
                'postal_code' => '150-0043',
                'phone' => '+81123456789',
                'email' => 'tokyo@techstore.com',
                'is_active' => 1,
                'created_at' => '2024-01-06 10:00:00',
            ],
            [
                'name' => 'Tech Store Sydney',
                'address' => 'George Street, 100',
                'city' => 'Sydney',
                'country' => 'Australia',
                'postal_code' => '2000',
                'phone' => '+61212345678',
                'email' => 'sydney@techstore.com',
                'is_active' => 0,
                'created_at' => '2024-01-07 10:00:00',
            ],
            [
                'name' => 'Tech Store Berlin',
                'address' => 'Kurfürstendamm, 234',
                'city' => 'Berlin',
                'country' => 'Germany',
                'postal_code' => '10719',
                'phone' => '+493012345678',
                'email' => 'berlin@techstore.com',
                'is_active' => 1,
                'created_at' => '2024-01-08 10:00:00',
            ],
            [
                'name' => 'Tech Store Dubai',
                'address' => 'Sheikh Zayed Road, 1000',
                'city' => 'Dubai',
                'country' => 'United Arab Emirates',
                'postal_code' => '00000',
                'phone' => '+971501234567',
                'email' => 'dubai@techstore.com',
                'is_active' => 1,
                'created_at' => '2024-01-09 10:00:00',
            ],
            [
                'name' => 'Tech Store Singapore',
                'address' => 'Orchard Road, 123',
                'city' => 'Singapore',
                'country' => 'Singapore',
                'postal_code' => '238859',
                'phone' => '+6512345678',
                'email' => 'singapore@techstore.com',
                'is_active' => 0,
                'created_at' => '2024-01-10 10:00:00',
            ],
        ];

        $sql = 'INSERT INTO stores (name, address, city, country, postal_code, phone, email, is_active, created_at) 
                VALUES (:name, :address, :city, :country, :postal_code, :phone, :email, :is_active, :created_at)';

        $stmt = $this->db->prepare($sql);

        foreach ($stores as $store) {
            $stmt->execute([
                'name' => $store['name'],
                'address' => $store['address'],
                'city' => $store['city'],
                'country' => $store['country'],
                'postal_code' => $store['postal_code'],
                'phone' => $store['phone'],
                'email' => $store['email'],
                'is_active' => $store['is_active'],
                'created_at' => $store['created_at'],
            ]);
        }
    }

    public function down(): void
    {
        $this->db->exec('TRUNCATE TABLE stores');
    }
}
