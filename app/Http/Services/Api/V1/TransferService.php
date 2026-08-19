<?php

namespace App\Http\Services\Api\V1;

use App\DTOs\V1\Transfer\TransferDTO;
use App\Events\V1\TransferCompleted;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransferService
{
    public function __construct(private Transfer $transfer, private AuthorizationService $authorizationService) {}

    public function store(TransferDTO $dto)
    {

        if (!$this->authorizationService->authorize()) {
            throw new \Exception('Transferência não autorizada pelo serviço externo.');
        }

        return DB::transaction(function () use ($dto) {

            $transfer = $this->transfer->create($dto->toArray());

            $this->debitTransfer($dto->payer, $dto->value);

            $this->creditTransfer($dto->payee, $dto->value);

            TransferCompleted::dispatch($transfer);

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
