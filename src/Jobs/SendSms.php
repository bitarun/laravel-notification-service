<?php

namespace Bitarun\LaravelNotificationService\Jobs;

use Bitarun\LaravelNotificationService\DTOs\NotificationRecipient;
use Bitarun\LaravelNotificationService\Notification;
use Bitarun\LaravelNotificationService\Providers\Sms;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendSms implements ShouldQueue
{
    use Queueable;

    private NotificationRecipient $recipient;
    private string $text;

    /**
     * Create a new job instance.
     */
    public function __construct(NotificationRecipient $recipient, string $text)
    {
        $this->recipient = $recipient;
        $this->text = $text;
    }

    /**
     * Execute the job.
     * @throws \Exception
     */
    public function handle(): void
    {
        (new Sms($this->recipient, $this->text))->send();
    }
}
