<?php

namespace Tests\Feature\Controllers\v1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Support\Str;

class AuthControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install', ['--no-interaction' => true,]);
        // Artisan::call('migrate',['-vvv' => true]);
        // Artisan::call('passport:install',['-vvv' => true]);
        $client_repository = new ClientRepository();
        $client = $client_repository->createPersonalAccessClient(
            null,
            'Test Personal Access Client',
            'http://localhost'
        );
        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    public function test_api_login(): void
    {
        $user = User::factory()->create();
        $this->postJson(route('login.api'), [
            'username' => $user->username,
            'password' => 'password',
        ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'message',
                'errors',
                'data' => [
                    'name',
                    'username',
                    'email',
                    'api_token',
                ],
            ]);
    }
    public function test_api_logout(): void
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $accessToken = $user->createToken('full_stack_app')->accessToken;
        Passport::actingAs($user);
        $this->postJson(route('logout.api'), [], ['Authorization' => "Bearer {$accessToken}"])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'message',
                'errors',
                'data',
            ]);
    }
}
