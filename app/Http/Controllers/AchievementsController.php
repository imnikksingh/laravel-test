<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Services\UnlockedAchievementsService;
use App\Services\NextAvailableAchievementsService;
use App\Services\BadgeInformationService;

class AchievementsController extends Controller
{
    protected $unlockedService;
    protected $nextAvailableService;
    protected $badgeService;

    public function __construct(UnlockedAchievementsService $unlockedService, NextAvailableAchievementsService $nextAvailableService, BadgeInformationService $badgeService)
    {
        $this->unlockedService = $unlockedService;
        $this->nextAvailableService = $nextAvailableService;
        $this->badgeService = $badgeService;
    }

    public function index(User $user)
    {
        $unlockedAchievements = $this->unlockedService->getUnlockedAchievements($user);
        $nextAvailableAchievements = $this->nextAvailableService->getNextAvailableAchievements($user);
        $badgeInformation = $this->badgeService->getBadgeInformation($user);

        return [
            'unlocked_achievements' => $unlockedAchievements,
            'next_available_achievements' => $nextAvailableAchievements,
            'current_badge' => $badgeInformation['current_badge'],
            'next_badge' => $badgeInformation['next_badge'],
            'remaining_to_unlock_next_badge' => $badgeInformation['remaining_to_unlock_next_badge'],
        ];
    }
}