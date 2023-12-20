<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function testMustEnterEmailAndPassword()
    {
        $this->json('POST', 'api/session')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The email field is required. (and 1 more error)",
                "errors" => [
                    'email' => ["The email field is required."],
                    'password' => ["The password field is required."],
                ]
            ]);
    }

    public function testFailedLogin()
    {
        $userData = [
            "email" => "fandy2@mail.com",
            "password" => "12345"
        ];
        $this->json('POST', 'api/session', $userData, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJsonStructure([
                "ok",
                "err",
                "msg"
            ]);
    }

    public function testSuccessfullLogin()
    {
        $userData = [
            "email" => "fandy2@mail.com",
            "password" => "123456"
        ];
        $this->json('POST', 'api/session', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "ok",
                "data" => [
                    "user" => [
                        "id",
                        "name",
                        "email",
                        "email_verified_at",
                        "created_at",
                        "updated_at"
                    ],
                    "access_token",
                    "refresh_token"
                ]
            ]);
    }
}
