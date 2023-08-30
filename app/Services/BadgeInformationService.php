<?php

namespace App\Services;

use App\Models\User;

class BadgeInformationService
{
    public function getBadgeInformation(User $user)
    {
        $badgeInformation = [
            ['name' => 'Beginner', 'required_achievements' => 0],
            ['name' => 'Intermediate', 'required_achievements' => 4],
            ['name' => 'Advanced', 'required_achievements' => 8],
            ['name' => 'Master', 'required_achievements' => 10],
        ];

        $currentBadge = $user->currentBadge();

        if (!$currentBadge) {
            $currentBadge = 'Beginner'; // Default to the first badge if no current badge
        }

        $nextBadgeIndex = collect($badgeInformation)
            ->search(function ($badge) use ($currentBadge) {
                return $badge['name'] === $currentBadge;
            }) + 1;

        $nextBadge = $nextBadgeIndex < count($badgeInformation)
            ? $badgeInformation[$nextBadgeIndex]['name']
            : null;

        $remainingToUnlockNextBadge = $nextBadge
            ? $badgeInformation[$nextBadgeIndex]['required_achievements'] - $user->unlockedAchievementsCount()
            : 0;

        return [
            'current_badge' => $currentBadge,
            'next_badge' => $nextBadge,
            'remaining_to_unlock_next_badge' => $remainingToUnlockNextBadge,
        ];
    }
}
