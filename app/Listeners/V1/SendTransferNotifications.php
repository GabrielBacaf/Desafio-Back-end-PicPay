<?php

namespace App\Listeners\V1;

use App\DTOs\V1\NotificationPayloadDTO;
use App\Events\V1\TransferCompleted;
use Illuminate\Support\Facades\Http;

class SendTransferNotifications
{
    public bool $afterCommit = true;

    public int $tries = 5;

    public int $backoff = 10;

    public function handle(TransferCompleted $event): void
    {
        $url = config('services.external_notification_service_url');

        $payload = NotificationPayloadDTO::fromModel($event->transfer)->toArray();

        $response = Http::post($url, $payload);

        if ($response->failed()) {
            throw new \Exception('Serviço de notificação indisponível.');
        }
    }
}
