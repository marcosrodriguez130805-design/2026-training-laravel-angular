<?php

namespace App\User\Infrastructure\Entrypoint\Http;

use App\User\Application\LoginUser\LoginUser;
use App\User\Application\LoginUser\LoginUserResponse;
use Illuminate\Http\Request;

class LoginUserController
{
    private LoginUser $loginUser;

    public function __construct(LoginUser $loginUser)
    {
        $this->loginUser = $loginUser;
    }

    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $response = ($this->loginUser)($request->input('email'), $request->input('password'));

        return response()->json([
            'id' => $response->id,
            'name' => $response->name,
            'email' => $response->email,
            'token' => $response->token,
        ]);
    }
}