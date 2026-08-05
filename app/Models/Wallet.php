<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hasSufficientBalance(float $value): bool
    {
        return $this->balance >= $value;
    }

    public function debitTransfer(float $value): void
    {
        $this->balance -= $value;
    }

    public function creditTransfer(float $value): void
    {
        $this->balance += $value;
    }

}
