<?php

namespace Bitarun\LaravelNotificationService;

use Bitarun\LaravelNotificationService\DTOs\NotificationRecipient;
use Bitarun\LaravelNotificationService\Providers\Contracts\Provider;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailable;

/**
 * @method static void sendSms(NotificationRecipient|string $phoneNumber, string $text)
 * @method static void sendEmail(NotificationRecipient|string $to, Mailable $mailable)
 * @method static Notification queue(bool $queue = true)
 * @method static Notification now()
 */
class Notification
{
    protected ?bool $queued = null;

    public function queue(bool $queue = true): static
    {
        $this->queued = $queue;
        return $this;
    }

    public function now(): static
    {
        $this->queued = false;
        return $this;
    }

    /**
     * @throws Exception
     */

    public function __call($method, $arguments)
    {
        $providerPath = __NAMESPACE__ . '\Providers\\' . substr($method, 4);
        if (!class_exists($providerPath)) {
            throw new Exception("The provider does not exists");
        }

        if (isset($arguments[0]) && is_string($arguments[0])) {
            $arguments[0] = $providerPath::resolveRecipient($arguments[0]);
        }

        $shouldQueue = $this->queued ?? config('notification.queue.enabled');

        if ($shouldQueue) {
            $jobClass = __NAMESPACE__ . '\Jobs\\' . ucfirst($method);
            if (class_exists($jobClass)) {
                return $jobClass::dispatch(... $arguments)
                    ->onConnection(config('notification.queue.connection'))
                    ->onQueue(config('notification.queue.name'));
            }
        }

        $this->sendNow($providerPath, $arguments);
    }

    /**
     * @throws Exception
     */
    protected function sendNow(string $providerPath, array $arguments): void
    {
        $providerInstance = new $providerPath(... $arguments);

        if (!$providerInstance instanceof Provider) {
            throw new Exception("The class must implements " . Provider::class);
        }

        $providerInstance->send();
    }
}
