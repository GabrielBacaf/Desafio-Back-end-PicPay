<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreTransferRequest;
use App\Http\Resources\Api\V1\TransferResource;
use App\Http\Services\Api\V1\TransferService;

class TransferController extends Controller
{
    public function __construct(private TransferService $transferService) {}

    public function store(StoreTransferRequest $transfer)
    {
        $transfer = $this->transferService->store($transfer->validated());

        return response()->json([
            'message' => 'Transferência realizada com sucesso',
            'data' => new TransferResource($transfer)
        ], 201);
    }
}
