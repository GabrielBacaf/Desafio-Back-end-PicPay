<?php

namespace App\DTOs\V1;

use App\Models\Transfer;
use Illuminate\Contracts\Support\Arrayable;


readonly class NotificationPayloadDTO implements Arrayable
{
    public function __construct(
        public int $transferId,
        public int $payeeId,
        public float $value

    ) {}

    public static function fromModel(Transfer $transfer): self
    {
        return new self(
            $transfer->id,
            $transfer->payee_id,
            $transfer->value
        );
    }

    public function toArray(): array
    {
        return [
            'transfer_id' => $this->transferId,
            'payee_id' => $this->payeeId,
            'value' => $this->value
        ];
    }
}
