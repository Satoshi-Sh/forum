<?php

use App\Models\Post;

it('uses title case for titles', function () {
    $post = Post::factory()->create(['title' => 'test title']);
    expect($post->title)->toBe('Test Title');
});
