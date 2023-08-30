<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Event;

class AchievementService
{
    public function unlockLessonWatchedAchievements(User $user)
    {
        $lessonsWatchedCount = $user->watched()->count();

        $lessonWatchedAchievements = [
            1 => 'First Lesson Watched',
            5 => '5 Lessons Watched',
            10 => '10 Lessons Watched',
            25 => '25 Lessons Watched',
            50 => '50 Lessons Watched',
        ];

        foreach ($lessonWatchedAchievements as $threshold => $achievementName) {
            if ($lessonsWatchedCount >= $threshold) {
                $this->unlockAchievement($user, $achievementName);
            }
        }
    }

    public function unlockCommentWrittenAchievements(User $user)
    {
        $commentsWrittenCount = $user->comments()->count();

        $commentWrittenAchievements = [
            1 => 'First Comment Written',
            3 => '3 Comments Written',
            5 => '5 Comments Written',
            10 => '10 Comments Written',
            20 => '20 Comments Written',
        ];

        foreach ($commentWrittenAchievements as $threshold => $achievementName) {
            if ($commentsWrittenCount >= $threshold) {
                $this->unlockAchievement($user, $achievementName);
            }
        }
    }

    protected function unlockAchievement(User $user, string $achievementName)
    {
        Event::dispatch('AchievementUnlocked', [
            'achievement_name' => $achievementName,
            'user' => $user,
        ]);

        $this->checkAndUnlockBadge($user);
    }

    protected function checkAndUnlockBadge(User $user)
    {
        $unlockedAchievementsCount = $user->unlockedAchievements()->count();

        $badgeMapping = [
            'Beginner' => 0,
            'Intermediate' => 4,
            'Advanced' => 8,
            'Master' => 10,
        ];

        $currentBadge = $user->currentBadge();

        foreach ($badgeMapping as $badgeName => $requiredAchievements) {
            if ($unlockedAchievementsCount >= $requiredAchievements && $currentBadge !== $badgeName) {
                $this->unlockBadge($user, $badgeName);
                break;
            }
        }
    }

    protected function unlockBadge(User $user, string $badgeName)
    {
        Event::dispatch('BadgeUnlocked', [
            'badge_name' => $badgeName,
            'user' => $user,
        ]);
    }
}
