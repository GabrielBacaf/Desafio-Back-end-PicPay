<?php

namespace App\Http\Requests\Api\V1;

use App\Rules\Api\V1\CheckIfRetailerRule;
use App\Rules\Api\V1\CheckUserBalanceRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransferRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payer_id' => ['required', 'integer', 'exists:users,id', new CheckIfRetailerRule(), new CheckUserBalanceRule()],
            'payee_id' => ['required', 'integer', 'exists:users,id'],
            'value' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function attributes()
    {
        return [
            'payer_id' => 'ID do pagador',
            'payee_id' => 'ID do recebedor',
            'value' => 'Valor da transferência',
        ];
    }
}
