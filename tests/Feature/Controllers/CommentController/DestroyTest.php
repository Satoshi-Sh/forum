<?php

use App\Models\Comment;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

it('requires authentication', function () {
    delete(route('comments.destroy', Comment::factory()->create()))
        ->assertRedirect(route('login'));
});

it('can delete a comment', function () {
    $comment = Comment::factory()->create();
    actingAs($comment->user)->delete(route('comments.destroy', $comment));
    $this->assertModelMissing($comment);
});

it('redirect to the posts index page', function () {
    $comment = Comment::factory()->create();
    actingAs($comment->user)
        ->delete(route('comments.destroy', $comment))
        ->assertRedirect(route('posts.show', $comment->post_id));
});

it('cannot delete a comment by others', function () {
    $comment = Comment::factory()->create();
    $otherUser = User::factory()->create();
    actingAs($otherUser)->delete(route('comments.destroy', $comment))
        ->assertForbidden();
    $this->assertModelExists($comment);
});

it('prevent deleting a comment over an hour ago', function () {
    $this->freezeTime();
    $comment = Comment::factory()->create();

    $this->travel(1)->hour();

    actingAs($comment->user)->delete(route('comments.destroy', $comment))
        ->assertForbidden();
    $this->assertModelExists($comment);
});

it('redirect to the post show page with the page query parameter', function () {
    $comment = Comment::factory()->create();
    actingAs($comment->user)
        ->delete(route('comments.destroy', ['comment' => $comment, 'page' => 2]))
        ->assertRedirect(route('posts.show', ['post' => $comment->post_id, 'page' => 2]));
});
