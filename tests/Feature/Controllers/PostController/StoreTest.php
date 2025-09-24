<?php

use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->validData = fn() => [
        'title' => 'Title',
        'topic_id' => Topic::factory()->create()->getKey(),
        'body' => 'body',
    ];
});

it('requires authentication', function () {
    post(route('posts.store'))->assertRedirect(route('login'));
});

it('store a post', function () {
    $user = User::factory()->create();
    $data = value($this->validData);

    actingAs($user)->post(route('posts.store'),
        $data);
    $this->assertDataBaseHas('posts', [
        'user_id' => $user->id,
        ...$data,
    ]);
});

it('redirects to the post show page', function () {
    $user = User::factory()->create();
    actingAs($user)->post(route('posts.store'), value($this->validData))
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});

it('requires valid data', function (array $badData, array|string $errors) {
    actingAs(User::factory()->create())
        ->post(route('posts.store'),
            [...value($this->validData), ...$badData])
        ->assertInvalid($errors);
})->with([
    [['title' => null], 'title'],
    [['title' => 1], 'title'],
    [['title' => true], 'title'],
    [['title' => str_repeat('a', 256)], 'title'],
    [['topic_id' => null], 'topic_id'],
    [['topic_id' => -1], 'topic_id'],
    [['body' => null], 'body'],
    [['body' => 1], 'body'],
    [['body' => true], 'body'],
    [['body' => str_repeat('a', 3001)], 'body']

]);


