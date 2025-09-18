<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('requires authntication', function () {
    get(route('posts.create'))->assertRedirect(route('login'));
});

it('returns the correct component', function () {
    $user = User::factory()->create();
    actingAs(($user))
        ->get(route('posts.create'))
        ->assertComponent('Posts/Create');
});
