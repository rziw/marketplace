<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function userLoginsSuccessfully()
    {
        $user = factory(User::class)->create();
        $loginUrl = config('app.url') . '/api/login';

        $response = $this->json('POST', $loginUrl, [
            'email' => $user->email,
            'password' => '123456'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'token'
            ]);
    }

    public function testRequiresEmailAndLogin()
    {
        $loginUrl = config('app.url') . '/api/login';

        $this->json('POST', $loginUrl)
            ->assertStatus(422)
            ->assertJson(['errors' =>
                [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]]);
    }
}
