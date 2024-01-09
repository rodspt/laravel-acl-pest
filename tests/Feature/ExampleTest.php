<?php

use function Pest\Laravel\getJson;

/*
   Dump
    getJson('/', ['Content-Type' => 'application/json'])->dump();
 * */

it('should return status code 200', function () {
    getJson('/', [ 'Content-Type' => 'application/json'])->assertStatus(200);
});

it('should return status code 200 short', fn () => getJson('/')->assertOk());
