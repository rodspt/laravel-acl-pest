<?php

use function Pest\Laravel\postJson;
use App\Models\User;
use App\Models\Permission;

it('unauthenticated user cannot get our data', function () {
    postJson(route('auth.me'), [],[])
        ->assertJson([
            'message' => 'Unauthenticated.'
        ])
        ->assertStatus(401);
});


it('should return user with our data', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test_e2e')->plainTextToken;

    postJson(route('auth.me'), [], [
        'Authorization' => "Bearer {$token}"
    ])
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'permissions' => []
            ]
        ])
        ->assertOk();
});


it('should return user with our data and our permissions', function () {
    Permission::factory()->count(10)->create();
    $permissionsIds = Permission::factory()->count(10)->create()->pluck('id')->toArray();
    $user = User::factory()->create();
    $user->permissions()->attach($permissionsIds);
    $token = $user->createToken('test_e2e')->plainTextToken;

    postJson(route('auth.me'), [], [
        'Authorization' => "Bearer {$token}"
    ])
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'permissions' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ]
            ]
        ])
        ->assertJsonCount(10, 'data.permissions')
        ->assertOk();
});
