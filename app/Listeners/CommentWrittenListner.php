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
        $user = $event->user;

        new Comment(['body' => $commentBody, 'user_id' => $user]);

        $this->achievementService->unlockCommentWrittenAchievements($user);
    }
}