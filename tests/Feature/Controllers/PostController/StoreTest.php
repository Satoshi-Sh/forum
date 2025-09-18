<?php

use App\Models\Post;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->validData = [
        'title' => 'Title',
        'body' => 'body',
    ];
});

it('requires authentication', function () {
    post(route('posts.store'))->assertRedirect(route('login'));
});

it('store a post', function () {
    $user = User::factory()->create();

    actingAs($user)->post(route('posts.store'),
        $this->validData);
    $this->assertDataBaseHas('posts', [
        'user_id' => $user->id,
        ...$this->validData,
    ]);
});

it('redirects to the post show page', function () {
    $user = User::factory()->create();
    $data = [
        'title' => 'title',
        'body' => 'body',
    ];
    actingAs($user)->post(route('posts.store'), $data)
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});

it('requires valid data', function (array $badData, array|string $errors) {
    actingAs(User::factory()->create())
        ->post(route('posts.store'),
            [...$this->validData, ...$badData])
        ->assertInvalid($errors);
})->with([
    [['title' => null], 'title'],
    [['title' => 1], 'title'],
    [['title' => true], 'title'],
    [['title' => str_repeat('a', 256)], 'title'],
    [['body' => null], 'body'],
    [['body' => 1], 'body'],
    [['body' => true], 'body'],
    [['body' => str_repeat('a', 3001)], 'body']

]);


