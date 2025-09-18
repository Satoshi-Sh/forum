<?php

use App\Models\Comment;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;


it('requires authentication', function () {
    $comment = Comment::factory()->create();
    put(route('comments.update', $comment))
        ->assertRedirect(route('login'));
});
it('can update a comment', function () {
    $comment = Comment::factory()->create();
    actingAs($comment->user)->put(route('comments.update', $comment), ['body' => 'Updated body']);
    $this->assertDataBaseHas('comments', [
        'post_id' => $comment->post_id,
        'user_id' => $comment->user->id,
        'body' => 'Updated body',
    ]);
});
it('redirects to the post show page', function () {
    $comment = Comment::factory()->create();
    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => 'Updated body'])
        ->assertRedirect($comment->post->showRoute());
});

it('redirects to the correct page of comments', function () {
    $comment = Comment::factory()->create();
    actingAs($comment->user)
        ->put(route('comments.update', ['comment' => $comment, 'page' => 2]), ['body' => 'Updated body'])
        ->assertRedirect($comment->post->showRoute(['page' => 2]));
});
it('cannot update a comment from another user', function () {
    $comment = Comment::factory()->create();
    $otherUser = User::factory()->create();
    actingAs($otherUser)->put(route('comments.update', $comment), ['body' => 'Updated body'])
        ->assertForbidden();
    $this->assertModelExists($comment);
});

it('require a valid body', function ($value) {
    $comment = Comment::factory()->create();

    actingAs($comment->user)->put(route('comments.update', $comment), [
        'body' => $value,
    ])
        ->assertInvalid('body');
})->with([
    null,
    1,
    1.5,
    true,
    str_repeat('a', 2501)
]);
