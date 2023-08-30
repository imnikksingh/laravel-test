<?php

namespace Tests\Unit\Events;

use App\Events\CommentWritten;
use App\Models\Comment;
// use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class CommentWrittenTest extends TestCase
{
    // use RefreshDatabase;

    public function testCommentWrittenEvent()
    {
        $comment = Comment::factory()->create();

        $event = new CommentWritten($comment);

        $this->assertInstanceOf(CommentWritten::class, $event);

        $this->assertEquals($comment, $event->comment);
    }
}