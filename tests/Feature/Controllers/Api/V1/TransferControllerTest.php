<?php

namespace Tests\Feature\Controllers\Api\V1;

use App\Http\Enums\Api\V1\TypeUsersEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class TransferControllerTest extends TestCase
{
    use RefreshDatabase;

    private array $defaultPayload;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->defaultPayload = [
            'value' => 50.00,
        ];
    }

    public function test_pode_processar_uma_transferencia_com_sucesso(): void
    {
        Http::fake([
            '*' => Http::response(['message' => 'Autorizado'], 200)
        ]);

        $payer = $this->createUserWithWallet(TypeUsersEnum::COMMON->value, 100.00);
        $payee = $this->createUserWithWallet(TypeUsersEnum::RETAILERS->value, 0.00);

        $payload = array_merge($this->defaultPayload, [
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
        ]);

        $response = $this->postJson('/api/v1/transfer', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Transferência realizada com sucesso',
            ]);

        $this->assertDatabaseHas('wallets', [
            'user_id' => $payer->id,
            'balance' => 50.00,
        ]);

        $this->assertDatabaseHas('wallets', [
            'user_id' => $payee->id,
            'balance' => 50.00,
        ]);
    }

    public function test_falha_se_pagador_tem_saldo_insuficiente(): void
    {
        $payer = $this->createUserWithWallet(TypeUsersEnum::COMMON->value, 10.00);
        $payee = $this->createUserWithWallet(TypeUsersEnum::RETAILERS->value, 0.00);

        $payload = array_merge($this->defaultPayload, [
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
        ]);

        $response = $this->postJson('/api/v1/transfer', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['payer_id']);
    }

    public function test_falha_se_pagador_e_lojista(): void
    {
        $payer = $this->createUserWithWallet(TypeUsersEnum::RETAILERS->value, 100.00);
        $payee = $this->createUserWithWallet(TypeUsersEnum::COMMON->value, 0.00);

        $payload = array_merge($this->defaultPayload, [
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
        ]);

        $response = $this->postJson('/api/v1/transfer', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['payer_id']);
    }
}
