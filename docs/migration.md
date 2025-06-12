## Migration System

The migration system is responsible for managing database changes in the application. It allows creating and reverting changes in a controlled and safe manner.

### Structure

The system consists of two main components:

1. **MigrateCommand** (`src/Commands/MigrateCommand.php`)
   - Contains all migration execution logic
   - Manages migration execution order
   - Provides progress feedback
   - Handles execution errors

2. **Migration Script** (`bin/migrate.php`)
   - Command line script to execute migrations
   - Supports `up` and `down` commands
   - Handles high-level errors

### How to Use

To execute migrations:

```bash
# Run all pending migrations
php bin/migrate.php

# Revert all migrations
php bin/migrate.php down
```

### Available Migrations

- `CreateStoresTable`: Creates the stores table
- `InsertStoresData`: Inserts initial store data

### Adding New Migrations

To add a new migration:

1. Create a new migration class in `src/Database/Migrations/`
2. Implement the `up()` and `down()` methods
3. Add the new migration to the `$migrations` array in `MigrateCommand`

Example of a migration:

```php
class NewMigration
{
    public function __construct(private PDO $db) {}

    public function up(): void
    {
        // Code to create/modify tables
    }

    public function down(): void
    {
        // Code to revert changes
    }
}
``` 
