<?php

namespace App\Http\Controllers;

use App\Enums\AccessTokenEnum;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return $this->sendResponse(
                false,
                [],
                ['err' => 'ERR_INVALID_CREDS', 'msg' => 'incorrect username or password', 'code' => 401]
            );
        }

        $accessToken = $user->createToken('access_token', [AccessTokenEnum::ACCESS_TOKEN], Carbon::now()->addMinutes(config('sanctum.expiration')));
        $refreshToken = $user->createToken('refresh_token', [AccessTokenEnum::REFRESH_ACCESS_TOKEN], Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration')));

        $res = [
            'user' => $user,
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken
        ];

        return $this->sendResponse(true, $res, ['code' => 200]);
    }

    public function register()
    {
        $data = [
            'name' => 'Fandy',
            'email' => 'fandy@mail.com',
            'password' => bcrypt('123456')
        ];

        $user = User::create($data);
        $accessToken = $user->createToken('access_token', [AccessTokenEnum::ACCESS_TOKEN], Carbon::now()->addSeconds(config('sanctum.expiration')));
        $refreshToken = $user->createToken('refresh_token', [AccessTokenEnum::REFRESH_ACCESS_TOKEN], Carbon::now()->addSeconds(config('sanctum.refresh_token_expiration')));

        $res = [
            'user' => $user,
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken
        ];

        return $this->sendResponse(true, $res, ['code' => 200]);
    }

    public function refreshToken(Request $request)
    {
        $accessToken = $request->user()->createToken('access_token', [AccessTokenEnum::ACCESS_TOKEN], Carbon::now()->addMinutes(config('sanctum.expiration')));
        return $this->sendResponse(true, ['access_token' => $accessToken->plainTextToken], ['code' => 200]);
    }

    public function logout ()
    {
        auth('sanctum')->user()->tokens()->delete();
        return response([
            'ok' => true
        ]);
    }
}
