<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class ApiAuthenticationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Authentication test .
     *
     * @return void
     */
    public function test_api_new_users_can_register()
    {
        if (!Features::hasApiFeatures()) {
            return $this->markTestSkipped('API support is not enabled.');
        }

        $response = $this->postJson('/api/register', [
            'name' => 'TestUser',
            'email' => 'TestUser@example.com',
            'phone' => '0644320022',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $response->assertOk(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('access-token')
                    ->has('user')
            )
            ->assertJsonPath('user.name', 'TestUser');
        // $this->assertAuthenticated('sanctum');
    }

    public function test_api_user_can_authenticate()
    {
        if (!Features::hasApiFeatures()) {
            return $this->markTestSkipped('API support is not enabled.');
        }

        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertOk(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('access-token')
            );
    }

    public function test_api_user_cannot_authenticate_with_invalid_password()
    {
        if (!Features::hasApiFeatures()) {
            return $this->markTestSkipped('API support is not enabled.');
        }

        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);
        $response->assertStatus(422);
    }
}
