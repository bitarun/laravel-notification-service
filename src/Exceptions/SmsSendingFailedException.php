<?php

namespace Bitarun\LaravelNotificationService\Exceptions;

use Exception;

class SmsSendingFailedException extends Exception
{
    public function __construct(
        string $message,
        public readonly ?int $statusCode = null
    ) {
        parent::__construct($message);
    }
}