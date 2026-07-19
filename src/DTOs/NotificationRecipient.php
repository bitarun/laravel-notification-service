<?php

namespace Bitarun\LaravelNotificationService\DTOs;

class NotificationRecipient
{
    public function __construct(
        public readonly ?string $email = null,
        public readonly ?string $phoneNumber = null,
        public readonly ?string $name = null
    ) {}

}