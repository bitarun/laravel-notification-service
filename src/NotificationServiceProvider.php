<?php

namespace Bitarun\LaravelNotificationService;

use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/notification.php',
            'notification'
        );

        $this->app->singleton('notification', function () {
            return new \Bitarun\LaravelNotificationService\Notification();
        });
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(
            __DIR__ . '/../resources/lang',
            'notification'
        );

        $this->publishes([
            __DIR__ . '/../config/notification.php' =>
                config_path('notification.php'),
        ], 'notification-config');
    }
}