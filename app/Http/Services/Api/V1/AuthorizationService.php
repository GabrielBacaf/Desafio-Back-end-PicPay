<?php

namespace App\Http\Services\Api\V1;

use Illuminate\Support\Facades\Http;

class AuthorizationService
{
    public function authorize(): bool
    {
        $url = config('services.external_transfer_service_url');
        $response = Http::get($url);
        
        if ($response->successful()) {
            return true;
        }

        return false;
    }
}
