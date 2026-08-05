<?php

namespace App\Rules\Api\V1;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

use Illuminate\Contracts\Validation\DataAwareRule;

class CheckUserBalanceRule implements ValidationRule, DataAwareRule
{
   
    protected array $data = [];

    public function setData(array $data): static
    {
        $this->data = $data;
 
        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
         $user = User::find($value);
         
         $transferValue = (float) ($this->data['value'] ?? 0);
         
        if (!$user->wallet->hasSufficientBalance($transferValue)) {
            $fail('Saldo insuficiente');
        }
    }
}
