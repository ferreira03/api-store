<?php

namespace App\Exceptions;

use Exception;

class StoreSaveException extends Exception
{
    public function __construct(string $message = 'Failed to save store', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
