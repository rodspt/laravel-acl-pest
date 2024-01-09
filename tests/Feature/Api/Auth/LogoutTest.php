<?php

use function Pest\Laravel\postJson;
use App\Models\User;


it('user authenticated should can logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test_e2e')->plainTextToken;

    postJson(route('auth.logout'), [], [
        'Authorization' => "Bearer {$token}"
    ])
    ->assertStatus(204);
});

it('user authenticated cannot logout', function () {
    postJson(route('auth.logout'), [], [])
        ->assertJson([
            'message' => 'Unauthenticated.'
        ])
        ->assertStatus(401);
});

