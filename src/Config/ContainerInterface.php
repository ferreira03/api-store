<?php

namespace App\Config;

interface ContainerInterface
{
    /**
     * Get a service from the container
     *
     * @param string $id The service identifier
     * @return mixed The service
     * @throws \Exception if the service is not found
     */
    public function get(string $id): mixed;
}
