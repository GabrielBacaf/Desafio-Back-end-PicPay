<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;

class UserController extends Controller
{
   
    public function store(StoreUserRequest $user)
    {
        $user = $user->validated();

        $user = User::create($user);

        return response()->json([
            'message' => 'Usuário criado com sucesso!',
            'data' => new UserResource($user)
        ], 201);
    }

}
