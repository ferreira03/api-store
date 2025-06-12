#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Commands\MigrateCommand;

/**
 * Database Migration Script
 * 
 * This script handles database migrations for the application.
 * It creates the necessary tables and populates them with initial data.
 */

// Main execution
try {
    $container = require __DIR__ . '/../config/container.php';
    $db = $container->get(PDO::class);
    $command = new MigrateCommand($db);

    $action = $argv[1] ?? 'up';
    
    if ($action === 'down') {
        $command->rollback();
    } else {
        $command->run();
    }
} catch (PDOException $e) {
    echo "✗ Database error: {$e->getMessage()}\n";
    exit(1);
} catch (\Exception $e) {
    echo "✗ Error: {$e->getMessage()}\n";
    exit(1);
}
