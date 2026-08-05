<?php

namespace App\Rules\Api\V1;

use App\Http\Enums\Api\V1\TypeUsersEnum;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CheckIfRetailerRule implements ValidationRule
{
    
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = User::find($value);

        if (!$user) {
            $fail('Usuario não encontrado.');
            return;
        }

        if ($user->type === TypeUsersEnum::RETAILERS->value) {
            $fail('Lojistas só recebem transferências, não enviam dinheiro para ninguém.');
            return;
        }
    }
   
}
