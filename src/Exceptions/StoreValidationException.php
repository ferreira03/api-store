<?php

namespace App\Exceptions;

use InvalidArgumentException;

class StoreValidationException extends InvalidArgumentException
{
    private const ERROR_MESSAGES = [
        'Invalid phone format' => 'Invalid phone format. Please use international format, e.g.: +5511999999999',
        'Invalid email format' => 'Invalid email format. Please use a valid email, e.g.: contact@example.com',
        'Store name is required' => 'Store name is required',
        'Store address is required' => 'Store address is required',
        'Store city is required' => 'Store city is required',
        'Store country is required' => 'Store country is required',
        'Store postal code is required' => 'Store postal code is required',
        'Store phone is required' => 'Store phone is required',
        'Store email is required' => 'Store email is required',
        'Store name must not exceed 100 characters' => 'Store name must not exceed 100 characters',
        'Store address must not exceed 200 characters' => 'Store address must not exceed 200 characters',
        'Store city must not exceed 100 characters' => 'Store city must not exceed 100 characters',
        'Store country must not exceed 100 characters' => 'Store country must not exceed 100 characters',
        'Store postal code must not exceed 20 characters' => 'Store postal code must not exceed 20 characters',
    ];

    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        $userMessage = self::ERROR_MESSAGES[$message] ?? $message;
        parent::__construct($userMessage, $code, $previous);
    }

    public function getTechnicalMessage(): string
    {
        return $this->getPrevious()?->getMessage() ?? $this->getMessage();
    }
}
