<?php

namespace Bitarun\LaravelNotificationService\Providers;

use Bitarun\LaravelNotificationService\DTOs\NotificationRecipient;
use Bitarun\LaravelNotificationService\Providers\Contracts\Provider;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class Email implements Provider
{
    private NotificationRecipient $recipient;
    private Mailable $mailable;

    public function __construct(NotificationRecipient $recipient, Mailable $mailable)
    {
        $this->recipient = $recipient;
        $this->mailable = $mailable;
    }

    public function send(): void
    {
        Mail::to($this->recipient->email)->send($this->mailable);
    }

    public static function resolveRecipient(string $value): NotificationRecipient
    {
        return new NotificationRecipient(email: $value);
    }
}
