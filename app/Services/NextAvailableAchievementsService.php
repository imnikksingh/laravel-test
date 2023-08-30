<?php

namespace App\Services;

use App\Models\User;

class NextAvailableAchievementsService
{
    public function getNextAvailableAchievements(User $user)
    {
        $unlockedAchievements = $user->unlockedAchievements()->pluck('name')->toArray();

        $allAchievements = [
            'First Lesson Watched',
            '5 Lessons Watched',
            '10 Lessons Watched',
            '25 Lessons Watched',
            '50 Lessons Watched',
            'First Comment Written',
            '3 Comments Written',
            '5 Comments Written',
            '10 Comments Written',
            '20 Comments Written',
        ];

        $nextAvailableAchievements = collect($allAchievements)
            ->reject(function ($achievement) use ($unlockedAchievements) {
                return in_array($achievement, $unlockedAchievements);
            })
            ->values()
            ->toArray();

        return $nextAvailableAchievements;
    }
}
