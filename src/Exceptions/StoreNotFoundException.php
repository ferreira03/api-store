<?php

namespace App\Exceptions;

use Exception;

class StoreNotFoundException extends Exception
{
    public function __construct(string $message = 'Store not found', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
