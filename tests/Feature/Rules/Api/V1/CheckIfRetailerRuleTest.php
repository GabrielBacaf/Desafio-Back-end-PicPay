<?php

namespace Tests\Feature\Rules\Api\V1;

use App\Http\Enums\Api\V1\TypeUsersEnum;
use App\Models\User;
use App\Rules\Api\V1\CheckIfRetailerRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckIfRetailerRuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_passa_quando_usuario_nao_e_lojista(): void
    {
        $user = User::factory()->create([
            'type' => TypeUsersEnum::COMMON->value,
        ]);

        $rule = new CheckIfRetailerRule();
        
        $failed = false;
        $rule->validate('payer_id', $user->id, function () use (&$failed) {
            $failed = true;
        });

        $this->assertFalse($failed, 'The rule should pass for a common user.');
    }

    public function test_falha_quando_usuario_e_lojista(): void
    {
        $user = User::factory()->create([
            'type' => TypeUsersEnum::RETAILERS->value,
        ]);

        $rule = new CheckIfRetailerRule();
        
        $failed = false;
        $errorMessage = '';
        $rule->validate('payer_id', $user->id, function ($message) use (&$failed, &$errorMessage) {
            $failed = true;
            $errorMessage = $message;
        });

        $this->assertTrue($failed, 'The rule should fail for a retailer user.');
        $this->assertEquals('Lojistas só recebem transferências, não enviam dinheiro para ninguém.', $errorMessage);
    }

    public function test_falha_quando_usuario_nao_existe(): void
    {
        $rule = new CheckIfRetailerRule();
        
        $failed = false;
        $errorMessage = '';
        $rule->validate('payer_id', 9999, function ($message) use (&$failed, &$errorMessage) {
            $failed = true;
            $errorMessage = $message;
        });

        $this->assertTrue($failed, 'The rule should fail when user does not exist.');
        $this->assertEquals('Usuario não encontrado.', $errorMessage);
    }
}
