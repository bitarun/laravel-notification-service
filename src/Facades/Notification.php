<?php

namespace Bitarun\LaravelNotificationService\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \Bitarun\LaravelNotificationService\Notification
 */
class Notification extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'notification';
    }
}