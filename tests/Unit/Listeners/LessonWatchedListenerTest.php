<?php
use App\Listeners\LessonWatchedListener;
use App\Events\LessonWatched;
use App\Models\Lesson;
use App\Models\User;
use App\Services\AchievementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class LessonWatchedListenerTest extends TestCase
{
    use RefreshDatabase;

    public function testLessonWatchedListenerHandlesEvent()
    {
        // Create a lesson and a user
        $lesson = Lesson::factory()->create();
        $user = User::factory()->create();

        // Mock the AchievementService instance
        $achievementService = Mockery::mock(AchievementService::class);
        $achievementService->shouldReceive('unlockLessonWatchedAchievements')->once();

        // Create an instance of the LessonWatchedListener and inject the mocked AchievementService
        $listener = new LessonWatchedListener($achievementService);

        // Create an instance of the LessonWatched event
        $event = new LessonWatched($lesson, $user);

        // Call the handle method of the listener
        $listener->handle($event);

        // Assert that the user watched the lesson
        $this->assertTrue($user->lessons->contains($lesson));

        // Mockery assertion
        Mockery::close();
    }
}