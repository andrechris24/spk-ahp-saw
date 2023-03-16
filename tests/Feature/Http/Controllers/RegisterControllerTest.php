<?php

namespace Tests\Feature\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_registration_screen()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }
    public function test_new_users_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
 
        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
    public function test_logout(){
        $response = $this->get('/logout');
        $response->assertStatus(302);
    }
}
