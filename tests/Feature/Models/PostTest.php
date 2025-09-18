<?php

use App\Models\Post;
use Illuminate\Support\Str;

it('uses title case for titles', function () {
    $post = Post::factory()->create(['title' => 'test title']);
    expect($post->title)->toBe('Test Title');
});

it('can generate a route to a show page', function () {
    $post = Post::factory()->create();
    expect($post->showRoute())->tobe(route('posts.show', [$post, Str::slug($post->title)]));
});


it('can generate additional query parameters on teh show route', function () {
    $post = Post::factory()->create();
    expect($post->showRoute(['page' => 2]))
        ->tobe(route('posts.show', [$post, Str::slug($post->title), 'page' => 2]));
});
