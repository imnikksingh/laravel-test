<?php 

use App\Events\LessonWatched;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonWatchedTest extends TestCase
{
    use RefreshDatabase;

    public function testLessonWatchedEvent()
    {
        // Create a lesson and a user
        $lesson = Lesson::factory()->create();
        $user = User::factory()->create();

        // Create an instance of the LessonWatched event
        $event = new LessonWatched($lesson, $user);

        // Assert that the event instance is created correctly
        $this->assertInstanceOf(LessonWatched::class, $event);

        // Assert that the event properties are set correctly
        $this->assertEquals($lesson, $event->lesson);
        $this->assertEquals($user, $event->user);
    }
}