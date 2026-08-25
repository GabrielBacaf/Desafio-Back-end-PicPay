<?php

namespace Tests\Feature\Controllers\Api\V1;

use App\Http\Enums\Api\V1\TypeUsersEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_pode_criar_um_novo_usuario(): void
    {
        $payload = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'document' => $this->faker->numerify('###########'), // 11 digits
            'password' => 'password123',
            'type' => TypeUsersEnum::COMMON->value,
        ];

        $response = $this->postJson('/api/v1/user', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'document',
                    'type',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $payload['email'],
            'document' => $payload['document'],
            'type' => $payload['type'],
        ]);
    }

    public function test_valida_campos_obrigatorios_ao_criar_usuario(): void
    {
        $response = $this->postJson('/api/v1/user', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'document', 'password', 'type']);
    }
}
