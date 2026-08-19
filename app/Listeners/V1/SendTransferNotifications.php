<?php

namespace App\Listeners\V1;

use App\DTOs\V1\NotificationPayloadDTO;
use App\Events\V1\TransferCompleted;
use Illuminate\Support\Facades\Http;
use App\Notifications\V1\TransferReceivedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTransferNotifications
{
    public bool $afterCommit = true;

    public int $tries = 5;

    public int $backoff = 10;

    public function handle(TransferCompleted $event): void
    {
        $transfer = $event->transfer;

        $url = config('services.external_services.notification_url');

        $payload = NotificationPayloadDTO::fromModel($event->transfer)->toArray();

        $response = Http::post($url, $payload);

        if ($response->failed()) {
            throw new \Exception('Serviço de notificação indisponível.');
        }
    }
}
