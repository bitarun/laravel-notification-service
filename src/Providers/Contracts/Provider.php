<?php

namespace Bitarun\LaravelNotificationService\Providers\Contracts;

use Bitarun\LaravelNotificationService\DTOs\NotificationRecipient;

interface Provider
{
    public function send(): void;

    public static function resolveRecipient(string $value): NotificationRecipient;
}
