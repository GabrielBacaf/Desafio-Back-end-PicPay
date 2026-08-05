<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Enums\Api\V1\TypeUsersEnum;
use Attribute;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

class StoreUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

   
    public function prepareForValidation()
    {
        $this->merge([
            'document' => preg_replace('/\\D/', '', (string) $this->document),
        ]);
    }


    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'document' => ['required', 'string', 'min:11', 'max:14', 'unique:users'],
            'type' => ['required', Rule::in(TypeUsersEnum::values())],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nome',
            'email' => 'E-mail',
            'password' => 'Senha',
            'document' => 'Documento',
            'type' => 'Tipo',
        ];
    }
}
