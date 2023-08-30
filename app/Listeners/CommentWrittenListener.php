<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Services\AchievementService;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentWrittenListener implements ShouldQueue
{
    protected $achievementService;

    public function __construct(AchievementService $achievementService)
    {
        $this->achievementService = $achievementService;
    }

    public function handle(CommentWritten $event)
    {
        $comment = $event->comment;
        $comment->save();

        $this->achievementService->unlockCommentWrittenAchievements($comment->user);
    }
}