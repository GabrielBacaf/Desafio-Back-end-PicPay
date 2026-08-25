<?php

namespace Tests\Feature\Rules\Api\V1;

use App\Rules\Api\V1\CheckUserBalanceRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Enums\Api\V1\TypeUsersEnum;
use Tests\TestCase;

class CheckUserBalanceRuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_passa_quando_usuario_tem_saldo_suficiente(): void
    {
        $user = $this->createUserWithWallet(TypeUsersEnum::COMMON->value, 100.00);

        $rule = new CheckUserBalanceRule();
        $rule->setData(['value' => 50.00]);
        
        $failed = false;
        $rule->validate('payer_id', $user->id, function () use (&$failed) {
            $failed = true;
        });

        $this->assertFalse($failed, 'The rule should pass when balance is sufficient.');
    }

    public function test_falha_quando_usuario_tem_saldo_insuficiente(): void
    {
        $user = $this->createUserWithWallet(TypeUsersEnum::COMMON->value, 10.00);

        $rule = new CheckUserBalanceRule();
        $rule->setData(['value' => 50.00]);
        
        $failed = false;
        $errorMessage = '';
        $rule->validate('payer_id', $user->id, function ($message) use (&$failed, &$errorMessage) {
            $failed = true;
            $errorMessage = $message;
        });

        $this->assertTrue($failed, 'The rule should fail when balance is insufficient.');
        $this->assertEquals('Saldo insuficiente', $errorMessage);
    }
}
