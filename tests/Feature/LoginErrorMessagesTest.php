<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class LoginErrorMessagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_il_affiche_les_erreurs_de_validation_sur_login(): void
    {
        $response = $this->from(route('login'))
            ->post(route('login.post'), [
                'login' => '',
                'password' => '',
            ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['login', 'password']);
    }

    public function test_il_affiche_le_message_pour_identifiants_incorrects(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => 'password',
        ]);

        $response = $this->from(route('login'))->followingRedirects()->post(route('login.post'), [
            'login' => 'admin',
            'password' => 'mauvais-mot-de-passe',
        ]);

        $response->assertOk();
        $response->assertSee('Les identifiants fournis sont incorrects.');
    }
}
