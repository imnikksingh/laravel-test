<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Services\AchievementService;
use Illuminate\Contracts\Queue\ShouldQueue;

class LessonWatchedListener implements ShouldQueue
{
    protected $achievementService;

    public function __construct(AchievementService $achievementService)
    {
        $this->achievementService = $achievementService;
    }

    public function handle(LessonWatched $event)
    {
        $lesson = $event->lesson;
        $user = $event->user;

        $user->watched()->save($lesson);

        $this->achievementService->unlockLessonWatchedAchievements($user);
    }
}