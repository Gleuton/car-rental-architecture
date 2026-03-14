<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

it('can refresh token and receive a jwt token payload', function () {
    $user = User::factory()->create();
    $token = (string) Auth::guard('api')->login($user);

    $this->withToken($token)
        ->postJson('/api/refresh')
        ->assertOk()
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
        ])
        ->assertJsonPath('token_type', 'Bearer');
});

it('invalidates the old token after refresh', function () {
    $user = User::factory()->create();
    $oldToken = (string) Auth::guard('api')->login($user);

    $this->withToken($oldToken)
        ->postJson('/api/refresh')
        ->assertOk();

    Auth::forgetGuards();

    $this->withToken($oldToken)
        ->getJson('/api/me')
        ->assertUnauthorized();
});

it('accepts the new token returned by refresh', function () {
    $user = User::factory()->create();
    $oldToken = (string) Auth::guard('api')->login($user);

    $refreshResponse = $this->withToken($oldToken)
        ->postJson('/api/refresh')
        ->assertOk();

    $newToken = $refreshResponse->json('access_token');

    expect($newToken)
        ->not->toBeEmpty()
        ->and($newToken)->not->toBe($oldToken);

    $this->withToken($newToken)
        ->getJson('/api/me')
        ->assertOk()
        ->assertJsonPath('data.id', $user->id);
});

it('returns 401 when refreshing without authentication', function () {
    $this->postJson('/api/refresh')
        ->assertUnauthorized()
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('returns 401 when refreshing with an invalid token', function () {
    $this->withToken('token.invalido.aqui')
        ->postJson('/api/refresh')
        ->assertUnauthorized();
});
