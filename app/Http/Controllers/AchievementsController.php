<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Services\UnlockedAchievementsService;
use App\Services\NextAvailableAchievementsService;
use App\Services\BadgeInformationService;

class AchievementsController extends Controller
{
    public function index(User $user, UnlockedAchievementsService $unlockedService, NextAvailableAchievementsService $nextAvailableService, BadgeInformationService $badgeService)
    {
        $unlockedAchievements = $unlockedService->getUnlockedAchievements($user);
        $nextAvailableAchievements = $nextAvailableService->getNextAvailableAchievements($user);
        $badgeInformation = $badgeService->getBadgeInformation($user);

        return [
            'unlocked_achievements' => $unlockedAchievements,
            'next_available_achievements' => $nextAvailableAchievements,
            'current_badge' => $badgeInformation['current_badge'],
            'next_badge' => $badgeInformation['next_badge'],
            'remaining_to_unlock_next_badge' => $badgeInformation['remaining_to_unlock_next_badge'],
        ];
    }
}
