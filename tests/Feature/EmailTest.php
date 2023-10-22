<?php

namespace Tests\Feature;

use App\Jobs\SendGenericEmailJob;
use App\Models\User;
use Bus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_email_api_respond_unauthenticated()
    {
        $data = [
            [
                'subject' => 'Test Subject 1',
                'body' => 'Test Body 1',
                'email' => 'test1@example.com',
            ],
        ];

        $response = $this->postJson(route('email.send', [
            'user' => 1,
            'api_token' => 'randomtoken',
        ]), ['data' => $data]);

        $response->assertStatus(401);
    }

    public function test_send_email_api_error_on_invalid_data()
    {
        $user = User::factory()->create();

        $data = [
            [
                'body' => 'Test Body 1',
                'email' => 'test1@example.com',
            ],
            [
                'subject' => 'Test Subject 2',
                'body' => 'Test Body 2',
            ],
        ];

        $response = $this->postJson(route('email.send', [
            'user' => $user->id,
            'api_token' => $user->api_token,
        ]), ['data' => $data]);

        $response->assertStatus(422);
    }

    public function test_send_email_api_respond_success()
    {
        $user = User::factory()->create();

        $data = [
            [
                'subject' => 'Test Subject 1',
                'body' => 'Test Body 1',
                'email' => 'test1@example.com',
            ],
            [
                'subject' => 'Test Subject 2',
                'body' => 'Test Body 2',
                'email' => 'test2@example.com',
            ],
        ];

        $response = $this->postJson(route('email.send', [
            'user' => $user->id,
            'api_token' => $user->api_token,
        ]), ['data' => $data]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Emails sent successfully']);
    }

    public function test_send_email_api_dispatches_email_job()
    {
        Bus::fake();
        $user = User::factory()->create();

        $data = [
            [
                'subject' => 'Test Subject 1',
                'body' => 'Test Body 1',
                'email' => 'test1@example.com',
            ],
            [
                'subject' => 'Test Subject 2',
                'body' => 'Test Body 2',
                'email' => 'test2@example.com',
            ],
        ];

        $response = $this->postJson(route('email.send', [
            'user' => $user->id,
            'api_token' => $user->api_token,
        ]), ['data' => $data]);

        $response->assertStatus(200);
        Bus::assertDispatched(SendGenericEmailJob::class, 2);
    }
}
