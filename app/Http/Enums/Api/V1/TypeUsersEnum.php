<?php

namespace App\Http\Enums\Api\V1;

enum TypeUsersEnum: string
{
    case COMMON = 'comuns ';
    case RETAILERS = 'lojistas';


    public static function values(): array
    {
        return array_map(fn ($type) => $type->value, self::cases());
    }
    
}