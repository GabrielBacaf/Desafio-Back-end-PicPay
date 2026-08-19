<?php

namespace App\Http\Services\Api\V1;

use App\Models\Transfer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransferService
{
    public function __construct(private Transfer $transfer, private AuthorizationService $authorizationService) {}

    public function store(array $data, User $payer, User $payee)
    {

        if (!$this->authorizationService->authorize()) {
            throw new \Exception('Transferência não autorizada pelo serviço externo.');
        }

        return DB::transaction(function () use ($data, $payer, $payee) {

            $transfer = $this->transfer->create($data);
            $this->debitTransfer($payer, $data['value']);
            $this->creditTransfer($payee, $data['value']);

            return $transfer;
        });
    }


    public function debitTransfer(User $payer, float $value): void
    {
        $payer->wallet->debitTransfer($value);
        $payer->wallet->save();
    }

    public function creditTransfer(User $payee, float $value): void
    {
        $payee->wallet->creditTransfer($value);
        $payee->wallet->save();
    }
}
