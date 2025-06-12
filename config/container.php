<?php

namespace App\Config;

use App\Controllers\Store\ListStoresController;
use App\Controllers\Store\CreateStoreController;
use App\Controllers\Store\DeleteStoreController;
use App\Controllers\Store\ShowStoreController;
use App\Controllers\Store\UpdateStoreController;
use App\Controllers\Store\PatchStoreController;
use App\Services\StoreService;
use App\Repositories\interfaces\StoreRepositoryInterface;
use App\Repositories\StoreRepository;

/**
 * Simple Dependency Injection Container
 * 
 * This container follows the PSR-11 Container Interface specification
 * and provides a simple way to manage dependencies in the application.
 */
class Container implements ContainerInterface
{
    /**
     * @var array<string, callable|object> The services registered in the container
     */
    private array $services = [];

    /**
     * Initialize the container with default services
     */
    public function __construct()
    {
        $this->services = [
            \PDO::class => function(): \PDO {
                $host = getenv('DB_HOST') ?: 'mysql';
                $dbname = getenv('DB_NAME') ?: 'store_db';
                $user = getenv('DB_USER') ?: 'store_user';
                $pass = getenv('DB_PASS') ?: 'root';

                return new \PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                    $user,
                    $pass,
                    [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                        \PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            },
            StoreRepositoryInterface::class => function(): StoreRepository {
                return new StoreRepository($this->get(\PDO::class));
            },
            StoreService::class => function(): StoreService {
                return new StoreService($this->get(StoreRepositoryInterface::class));
            },
            ListStoresController::class => function(): ListStoresController {
                return new ListStoresController($this->get(StoreService::class));
            },
            CreateStoreController::class => function(): CreateStoreController {
                return new CreateStoreController($this->get(StoreService::class));
            },
            ShowStoreController::class => function(): ShowStoreController {
                return new ShowStoreController($this->get(StoreService::class));
            },
            UpdateStoreController::class => function(): UpdateStoreController {
                return new UpdateStoreController($this->get(StoreService::class));
            },
            DeleteStoreController::class => function(): DeleteStoreController {
                return new DeleteStoreController($this->get(StoreService::class));
            },
            PatchStoreController::class => function(): PatchStoreController {
                return new PatchStoreController($this->get(StoreService::class));
            },
        ];
    }

    /**
     * Get a service from the container
     *
     * @param string $id The service identifier
     * @return mixed The service
     * @throws \Exception if the service is not found
     */
    public function get(string $id): mixed
    {
        if (!isset($this->services[$id])) {
            throw new \Exception("Service '$id' not found in container");
        }

        if (is_callable($this->services[$id])) {
            $this->services[$id] = $this->services[$id]();
        }

        return $this->services[$id];
    }
}

return new Container();
