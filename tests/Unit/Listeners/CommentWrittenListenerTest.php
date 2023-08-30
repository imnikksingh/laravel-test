<?php

namespace Tests\Unit\Listeners;

use App\Listeners\CommentWrittenListener;
use App\Events\CommentWritten;
use App\Models\Comment;
use App\Services\AchievementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
// use PHPUnit\Framework\TestCase;

use Tests\TestCase;

class CommentWrittenListenerTest extends TestCase
{
    // use RefreshDatabase;

    public function testCommentWrittenListenerHandlesEvent()
    {
        $comment = Comment::factory()->create();

        $achievementService = Mockery::mock(AchievementService::class);
        $achievementService->shouldReceive('unlockCommentWrittenAchievements')->once();

        $listener = new CommentWrittenListener($achievementService);

        $event = new CommentWritten($comment);

        $listener->handle($event);

        $this->assertDatabaseHas('comments', ['id' => $comment->id]);

        Mockery::close();
    }
}