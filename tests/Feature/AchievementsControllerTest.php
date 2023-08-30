<?php

use App\Http\Controllers\AchievementsController;
use App\Models\User;
use App\Services\UnlockedAchievementsService;
use App\Services\NextAvailableAchievementsService;
use App\Services\BadgeInformationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class AchievementsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAchievementsControllerReturnsData()
    {
        // Create a user
        $user = User::factory()->create();

        // Mock the services
        $unlockedService = Mockery::mock(UnlockedAchievementsService::class);
        $nextAvailableService = Mockery::mock(NextAvailableAchievementsService::class);
        $badgeService = Mockery::mock(BadgeInformationService::class);

        // Define the mocked data
        $unlockedAchievements = ['First Lesson Watched', 'First Comment Written'];
        $nextAvailableAchievements = ['5 Lessons Watched', '3 Comments Written'];
        $badgeInformation = [
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaining_to_unlock_next_badge' => 2,
        ];

        // Set up expectations for the services
        $unlockedService->shouldReceive('getUnlockedAchievements')->once()->andReturn($unlockedAchievements);
        $nextAvailableService->shouldReceive('getNextAvailableAchievements')->once()->andReturn($nextAvailableAchievements);
        $badgeService->shouldReceive('getBadgeInformation')->once()->andReturn($badgeInformation);

        // Create an instance of the AchievementsController with mocked services
        $controller = new AchievementsController($unlockedService, $nextAvailableService, $badgeService);

        // Call the index method on the controller
        $response = $controller->index($user);

        // Assert the JSON response structure and data
        $this->assertEquals([
            'unlocked_achievements' => $unlockedAchievements,
            'next_available_achievements' => $nextAvailableAchievements,
            'current_badge' => $badgeInformation['current_badge'],
            'next_badge' => $badgeInformation['next_badge'],
            'remaining_to_unlock_next_badge' => $badgeInformation['remaining_to_unlock_next_badge'],
        ], $response);

        // Mockery assertion
        Mockery::close();
    }
}