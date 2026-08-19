<?php

namespace App\DTOs\V1\Transfer;

use App\Models\User;

readonly class TransferDTO
{
    public function __construct(
        public User $payer,
        public User $payee,
        public float $value
    ) {}

    public function toArray(): array
    {
        return [
            'payer_id' => $this->payer->id,
            'payee_id' => $this->payee->id,
            'value'    => $this->value,
        ];
    }
    
}
