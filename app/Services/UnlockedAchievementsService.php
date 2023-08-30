<?php

namespace App\Services;

use App\Models\User;

class UnlockedAchievementsService
{
    public function getUnlockedAchievements(User $user)
    {
        return $user->unlockedAchievements()->pluck('name')->toArray();
    }
}
