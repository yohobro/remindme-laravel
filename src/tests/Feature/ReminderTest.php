<?php

namespace Tests\Feature;

use Tests\TestCase;

class ReminderTest extends TestCase
{
    public function testCreateReminder()
    {
        $userLogin = [
            'email' => 'fandy@mail.com',
            'password' => '123456'
        ];
        $login = $this->postJson('api/session', $userLogin);
        $login->assertStatus(200);
        $token = $login->getData()->data->access_token;

        $reminderData = [
            "title" => 'testing123',
            'description' => 'coba coba coba',
            'remind_at' => '2023-12-18 21:00:00',
            'event_at' => '2023-12-18 21:10:00'
        ];
        $this->postJson(
            'api/reminders',
            $reminderData,
            [
                'Accept' => 'application/json',
                'Authorization' => "Bearer $token"
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'ok',
                'data' => [
                    'id',
                    'title',
                    'description',
                    'remind_at',
                    'event_at'
                ]
            ]);
    }

    public function testShowReminder()
    {
        $userLogin = [
            'email' => 'fandy@mail.com',
            'password' => '123456'
        ];
        $login = $this->postJson('api/session', $userLogin);
        $login->assertStatus(200);
        $token = $login->getData()->data->access_token;

        $reminderData = [
            "title" => 'testing123',
            'description' => 'coba coba coba',
            'remind_at' => '2023-12-18 21:00:00',
            'event_at' => '2023-12-18 21:10:00'
        ];

        $reminder = $this->postJson(
            'api/reminders',
            $reminderData,
            [
                'Accept' => 'application/json',
                'Authorization' => "Bearer $token"
            ])
            ->assertStatus(200);
        $reminderId = $reminder->getData()->data->id;

        $this->getJson('api/reminders/' . $reminderId)
            ->assertStatus(200)
            ->assertJsonStructure([
                'ok',
                'data' => [
                    'id',
                    'title',
                    'description',
                    'remind_at',
                    'event_at'
                ]
            ]);
    }

    public function testUpdateReminder()
    {
        $userLogin = [
            'email' => 'fandy@mail.com',
            'password' => '123456'
        ];
        $login = $this->postJson('api/session', $userLogin);
        $login->assertStatus(200);
        $token = $login->getData()->data->access_token;

        $reminderData = [
            "title" => 'testing123',
            'description' => 'coba coba coba',
            'remind_at' => '2023-12-18 21:00:00',
            'event_at' => '2023-12-18 21:10:00'
        ];

        $reminder = $this->postJson(
            'api/reminders',
            $reminderData,
            [
                'Accept' => 'application/json',
                'Authorization' => "Bearer $token"
            ])
            ->assertStatus(200);
        $reminderId = $reminder->getData()->data->id;

        $this->putJson('api/reminders/' . $reminderId, ['title' => 'ok coy'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'ok',
                'data' => [
                    'id',
                    'title',
                    'description',
                    'remind_at',
                    'event_at'
                ]
            ]);
    }

    public function testDeleteReminder()
    {
        $userLogin = [
            'email' => 'fandy@mail.com',
            'password' => '123456'
        ];
        $login = $this->postJson('api/session', $userLogin);
        $login->assertStatus(200);
        $token = $login->getData()->data->access_token;

        $reminderData = [
            "title" => 'testing123',
            'description' => 'coba coba coba',
            'remind_at' => '2023-12-18 21:00:00',
            'event_at' => '2023-12-18 21:10:00'
        ];

        $reminder = $this->postJson(
            'api/reminders',
            $reminderData,
            [
                'Accept' => 'application/json',
                'Authorization' => "Bearer $token"
            ])
            ->assertStatus(200);
        $reminderId = $reminder->getData()->data->id;

        $this->deleteJson('api/reminders/' . $reminderId)
            ->assertStatus(200)
            ->assertJsonStructure([
                'ok'
            ]);
    }
}
