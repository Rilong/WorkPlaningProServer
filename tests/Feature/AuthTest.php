<?php

namespace Tests\Feature;

use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testUserLogin()
    {
        \App\User::create([
            'name' => 'Roman',
            'email' => 'romgnatyuk@gmail.com',
            'password' => \Hash::make('123456')
        ]);

        $response = $this->post('/api/login', [
            'email' => 'romgnatyuk@gmail.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);
        $response->assertSee('token');
    }

    public function testUserLoginFailed()
    {
        $response = $this->post('/api/login', [
            'email' => 'romgnatyuk@gmail.com',
            'password' => '1232'
        ]);

        $response->assertStatus(401);
    }

    public function testUserLogout() {
        $user = Passport::actingAs(factory(User::class)->create());
        $response = $this->actingAs($user)->post('/api/logout');

        $response->assertStatus(200);
    }
}
