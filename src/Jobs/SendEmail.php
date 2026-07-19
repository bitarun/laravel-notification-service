<?php

namespace Bitarun\LaravelNotificationService\Jobs;

use Bitarun\LaravelNotificationService\DTOs\NotificationRecipient;
use Bitarun\LaravelNotificationService\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailable;

class SendEmail implements ShouldQueue
{
    use Queueable;

    private NotificationRecipient $recipient;
    private Mailable $mailable;

    /**
     * Create a new job instance.
     */
    public function __construct(NotificationRecipient $recipient, Mailable $mailable)
    {
        $this->recipient = $recipient;
        $this->mailable = $mailable;
    }

    /**
     * Execute the job.
     */
    public function handle(Notification $notification)
    {
        return $notification->now()->sendEmail($this->recipient, $this->mailable);
    }
}
