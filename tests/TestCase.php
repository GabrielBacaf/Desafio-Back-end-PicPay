<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Helper to create a user with a wallet and specific balance.
     */
    protected function createUserWithWallet(string $type, float $balance): \App\Models\User
    {
        $user = \App\Models\User::factory()->create([
            'type' => $type,
        ]);
        
        \App\Models\Wallet::create([
            'user_id' => $user->id,
            'balance' => $balance,
        ]);

        return $user;
    }
}
