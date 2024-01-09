<?php

use function Pest\Laravel\postJson;
use App\Models\User;

it('should auth user', function () {
    $user = User::factory()->create();
    $data = [
        'email' => $user->email,
        'password' => 'password',
        'device_name' => 'e2e_test'
    ];

    postJson(route('auth.login'),$data)
        ->assertOk()
        ->assertJsonStructure(['token']);
});

it('should fail test auth', function () {
    $user = User::factory()->create();
    $data = [
        'email' => $user->email,
        'password' => 'passwordx',
        'device_name' => 'e2e_test'
    ];
    postJson(route('auth.login'),$data)
        ->assertStatus(422);
});

describe('validations', function(){
     it('should require email', function (){
         postJson(route('auth.login'),[
             'password' => 'password',
             'device_name' => 'e2e_test'
         ])
          ->assertJsonValidationErrors([
            'email' => trans('validation.required', ['attribute' => 'email'])
         ])
         ->assertStatus(422);
     });

    it('should require password', function (){
        $user = User::factory()->create();
        postJson(route('auth.login'),[
            'email' => $user->email,
            'device_name' => 'e2e_test'
        ])
        ->assertJsonValidationErrors([
           'password' => trans('validation.required', ['attribute' => 'password'])
        ])
        ->assertStatus(422);
    });

    it('should require device_name', function (){
        $user = User::factory()->create();
        postJson(route('auth.login'),[
            'email' => $user->email,
            'password' => 'password'
        ])
        ->assertJsonValidationErrors([
           'device_name' => trans('validation.required', ['attribute' => 'device name'])
        ])
        ->assertStatus(422);
    });

});
