<?php

namespace App\Commands;

use App\Database\Migrations\CreateStoresTable;
use App\Database\Migrations\InsertStoresData;
use PDO;
use PDOException;

class MigrateCommand
{
    private array $migrations;

    public function __construct(private PDO $db)
    {
        $this->migrations = [
            new CreateStoresTable($this->db),
            new InsertStoresData($this->db),
        ];
    }

    public function run(): void
    {
        foreach ($this->migrations as $migration) {
            $migrationName = get_class($migration);
            echo "Running migration: {$migrationName}\n";

            try {
                $migration->up();
                echo "✓ Migration {$migrationName} completed successfully\n";
            } catch (PDOException $e) {
                echo "✗ Database error in {$migrationName}: {$e->getMessage()}\n";

                throw $e;
            } catch (\Exception $e) {
                echo "✗ Error in {$migrationName}: {$e->getMessage()}\n";

                throw $e;
            }
        }
    }

    public function rollback(): void
    {
        foreach (array_reverse($this->migrations) as $migration) {
            $migrationName = get_class($migration);
            echo "Rolling back: {$migrationName}\n";

            try {
                $migration->down();
                echo "✓ Rollback of {$migrationName} completed successfully\n";
            } catch (PDOException $e) {
                echo "✗ Database error rolling back {$migrationName}: {$e->getMessage()}\n";

                throw $e;
            } catch (\Exception $e) {
                echo "✗ Error rolling back {$migrationName}: {$e->getMessage()}\n";

                throw $e;
            }
        }
    }
}
