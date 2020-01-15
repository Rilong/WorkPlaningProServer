<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRegisterUser()
    {
        $response = $this->post('/api/register', [
            'name' => 'Test',
            'email' => 'test@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(201);
    }

    public function testRegisterUserValidationName()
    {
        $response = $this->post('/api/register', [
            'name' => 'Tes',
            'email' => 'test1@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(400);
        $response->assertSee('name');
    }

    public function testRegisterUserValidationEmailExists()
    {
        $this->post('/api/register', [
            'name' => 'Test',
            'email' => 'test1@email.com',
            'password' => '123456'
        ]);
        $response = $this->post('/api/register', [
            'name' => 'Test',
            'email' => 'test1@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(400);
        $response->assertSee('email');
        $response->assertSee('The email has already been taken.');
    }

    public function testRegisterUserValidation()
    {
        $response = $this->post('/api/register');

        $response->assertStatus(400);
    }
}
