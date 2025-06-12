<?php

use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

return simpleDispatcher(function (RouteCollector $r) {
    // Store routes
    $r->addGroup('/api/v1', function (RouteCollector $r) {
        // GET /api/v1/stores - List all stores
        $r->get('/stores', 'App\Controllers\Store\ListStoresController');

        // GET /api/v1/stores/{id} - Get a specific store
        $r->get('/stores/{id:\d+}', 'App\Controllers\Store\ShowStoreController');

        // POST /api/v1/stores - Create a new store
        $r->post('/stores', 'App\Controllers\Store\CreateStoreController');

        // PUT /api/v1/stores/{id} - Update a store
        $r->put('/stores/{id:\d+}', 'App\Controllers\Store\UpdateStoreController');

        // DELETE /api/v1/stores/{id} - Delete a store
        $r->delete('/stores/{id:\d+}', 'App\Controllers\Store\DeleteStoreController');

        // PATCH /api/v1/stores/{id} - Update a store
        $r->patch('/stores/{id:\d+}', 'App\Controllers\Store\PatchStoreController');
    });
});
