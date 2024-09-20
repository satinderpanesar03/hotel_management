<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminLoginTest extends DuskTestCase
{
    use RefreshDatabase;

    public function test_successful_login()
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 1,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/admin/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/admin/dashboard')
                    ->assertSee('Login successfully');
        });
    }

    public function test_login_with_invalid_email()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'invalid@example.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/admin/login')
                    ->assertSee('Email does not exist');
        });
    }

    public function test_login_with_invalid_password()
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('correct-password'),
            'role' => 1,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/admin/login')
                    ->type('email', $user->email)
                    ->type('password', 'wrong-password')
                    ->press('Login')
                    ->assertPathIs('/admin/login')
                    ->assertSee('Invalid password');
        });
    }
}
