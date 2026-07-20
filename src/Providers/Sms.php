<?php

namespace Bitarun\LaravelNotificationService\Providers;

use Bitarun\LaravelNotificationService\DTOs\NotificationRecipient;
use Bitarun\LaravelNotificationService\Exceptions\SmsSendingFailedException;
use Bitarun\LaravelNotificationService\Exceptions\UserDoesNotHavePhoneNumber;
use Bitarun\LaravelNotificationService\Providers\Contracts\Provider;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class Sms implements Provider
{
    private NotificationRecipient $recipient;
    private string $text;

    public function __construct(NotificationRecipient $recipient, string $text)
    {
        $this->recipient = $recipient;
        $this->text = $text;
    }

    /**
     * @throws ConnectionException
     * @throws SmsSendingFailedException
     */
    public function send(): void
    {
        $apiKey = config('notification.sms.api_key');
        $url = config('notification.sms.url');
        $templateId = config('notification.sms.template_id');

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'text/plain',
            'x-api-key' => $apiKey,
        ];

        $response = Http::withoutVerifying()
            ->withHeaders($headers)
            ->post($url, [
                'mobile'     => $this->recipient->phoneNumber,
                'templateId' => $templateId,
                'parameters' => [
                    ['name' => 'Code', 'value' => '12345'],
                ],
            ]);

        if (! $response->successful()) {
            throw new SmsSendingFailedException(
                message: $response->body(),
                statusCode: $response->status()
            );
        }
    }

    public static function resolveRecipient(string $value): NotificationRecipient
    {
        return new NotificationRecipient(phoneNumber: $value);
    }
}
