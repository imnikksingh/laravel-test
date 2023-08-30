<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Lesson;
use App\Models\Comment;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function watched()
    {
        return $this->belongsToMany(Lesson::class, 'user_lessons')->wherePivot('watched', true);;
    }

    public function unlockedAchievementsCount()
    {
        $lessonsWatched = $this->watched()->count();
        $commentsWritten = $this->comments()->count();

        $achievements = [
            'First Lesson Watched' => $lessonsWatched >= 1,
            '5 Lessons Watched' => $lessonsWatched >= 5,
            '10 Lessons Watched' => $lessonsWatched >= 10,
            '25 Lessons Watched' => $lessonsWatched >= 25,
            '50 Lessons Watched' => $lessonsWatched >= 50,
            'First Comment Written' => $commentsWritten >= 1,
            '3 Comments Written' => $commentsWritten >= 3,
            '5 Comments Written' => $commentsWritten >= 5,
            '10 Comment Written' => $commentsWritten >= 10,
            '20 Comment Written' => $commentsWritten >= 20,
        ];

        return count(array_filter($achievements));
    }

    public function currentBadge()
    {
        $unlockedAchievementsCount = $this->unlockedAchievementsCount();

        if ($unlockedAchievementsCount >= 10) {
            return 'Master';
        } elseif ($unlockedAchievementsCount >= 8) {
            return 'Advanced';
        } elseif ($unlockedAchievementsCount >= 4) {
            return 'Intermediate';
        } else {
            return 'Beginner';
        }
    }

}