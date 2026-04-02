<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;
use App\Notifications\Auth\VerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'date_of_birth',
        'gender',
        'address',
        'profile_picture',
        'parent_id',
        'unique_number',
        'phone_number',
        'relationship',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'trial_ends_at' => 'datetime',
            'premium_ends_at' => 'datetime',
        ];
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Get the parent of this user (if this user is a child).
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the children of this user (if this user is a parent).
     */
    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'enrollments')->withPivot('status', 'chat_status', 'is_active')->withTimestamps();
    }



    public function getAgeAttribute()
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null;
    }

    public function observations()
    {
        return $this->hasMany(Observation::class, 'child_id');
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function programMessages()
    {
        return $this->hasMany(ProgramMessage::class);
    }

    public function reactionsGiven()
    {
        return $this->hasMany(MessageReaction::class);
    }

    public function reactionsReceived()
    {
        return $this->hasManyThrough(MessageReaction::class, ProgramMessage::class);
    }

    /**
     * Get the user's current rank based on level
     */
    public function getRankAttribute()
    {
        $level = floor($this->xp_points / 100) + 1;

        if ($level >= 50)
            return 'Legendary Mentor';
        if ($level >= 30)
            return 'Grandmaster';
        if ($level >= 20)
            return 'Master Explorer';
        if ($level >= 10)
            return 'Expert Voyager';
        if ($level >= 5)
            return 'Pro Learner';
        if ($level >= 3)
            return 'Achiever';
        if ($level >= 2)
            return 'Explorer';

        return 'Novice';
    }

    /**
     * Increment XP and check for level ups
     */
    public function addXp($points)
    {
        $this->xp_points += $points;
        $this->save();
    }

    public function achievements()
    {
        return $this->hasMany(UserAchievement::class);
    }

    /**
     * Get push subscriptions for this user
     */
    public function pushSubscriptions()
    {
        return $this->hasMany(PushSubscription::class);
    }

    /**
     * Get push notification logs for this user
     */
    public function pushNotificationLogs()
    {
        return $this->hasMany(PushNotificationLog::class);
    }

    /**
     * Check and award new achievements
     */
    public function checkAchievements()
    {
        // This is a bit redundant with the controller logic, but we'll use it to send notifications
        // In a real app, we might use a dedicated Achievement service
        $stats = [
            'total_lessons' => $this->lessonProgress()->whereNotNull('completed_at')->count(),
            'total_messages' => $this->programMessages()->count(),
            'total_loves_received' => $this->reactionsReceived()->where('type', 'love')->count(),
            'total_likes_received' => $this->reactionsReceived()->where('type', 'like')->count(),
            'total_given' => $this->reactionsGiven()->count(),
            'streak' => $this->streak,
            'level' => floor($this->xp_points / 100) + 1,
        ];

        $thresholds = [
            'first_step' => ['type' => 'lessons', 'value' => 3, 'name' => 'First Step'],
            'chatterbox' => ['type' => 'messages', 'value' => 50, 'name' => 'Circle Speaker'],
            'heart_of_gold' => ['type' => 'loves_received', 'value' => 50, 'name' => 'Heart of Gold'],
            'social_hero' => ['type' => 'given', 'value' => 100, 'name' => 'Social Hero'],
            'knowledge_seeker' => ['type' => 'lessons', 'value' => 20, 'name' => 'Knowledge Seeker'],
            'the_popular' => ['type' => 'likes_received', 'value' => 200, 'name' => 'The Popular'],
            'on_fire' => ['type' => 'streak', 'value' => 7, 'name' => 'Consistent Explorer'],
            'program_graduate' => ['type' => 'programs', 'value' => 1, 'name' => 'Program Graduate'],
            'trailblazer' => ['type' => 'lessons', 'value' => 50, 'name' => 'Trailblazer'],
            'committed' => ['type' => 'streak', 'value' => 21, 'name' => 'The Dedicated'],
            'voice_of_wisdom' => ['type' => 'messages', 'value' => 200, 'name' => 'Halaqah Legend'],
            'master_of_one' => ['type' => 'programs', 'value' => 3, 'name' => 'Master of One'],
            'rising_star' => ['type' => 'level', 'value' => 10, 'name' => 'Rising Star'],
            'consistent_scholar' => ['type' => 'streak', 'value' => 50, 'name' => 'Consistent Scholar'],
            'circle_pillar' => ['type' => 'messages', 'value' => 500, 'name' => 'Circle Pillar'],
            'journey_veteran' => ['type' => 'programs', 'value' => 5, 'name' => 'Journey Veteran'],
            'high_achiever' => ['type' => 'lessons', 'value' => 100, 'name' => 'High Achiever'],
            'elite_explorer' => ['type' => 'level', 'value' => 50, 'name' => 'Guardian of Knowledge'],
        ];

        // For programs count, we need a quick check
        $completedProgramsCount = null;

        foreach ($thresholds as $id => $data) {
            $unlocked = false;

            if ($data['type'] === 'lessons' && $stats['total_lessons'] >= $data['value'])
                $unlocked = true;
            if ($data['type'] === 'messages' && $stats['total_messages'] >= $data['value'])
                $unlocked = true;
            if ($data['type'] === 'loves_received' && $stats['total_loves_received'] >= $data['value'])
                $unlocked = true;
            if ($data['type'] === 'likes_received' && $stats['total_likes_received'] >= $data['value'])
                $unlocked = true;
            if ($data['type'] === 'given' && $stats['total_given'] >= $data['value'])
                $unlocked = true;
            if ($data['type'] === 'streak' && $stats['streak'] >= $data['value'])
                $unlocked = true;
            if ($data['type'] === 'level' && $stats['level'] >= $data['value'])
                $unlocked = true;

            if ($data['type'] === 'programs') {
                if ($completedProgramsCount === null) {
                    $completedProgramsCount = 0;
                    $progs = $this->programs()->with('contents')->get();
                    foreach ($progs as $p) {
                        $vis = $p->contents()->where('is_active', true)->count();
                        if ($vis === 0)
                            continue;
                        $done = $this->lessonProgress()->whereIn('program_content_id', $p->contents->pluck('id'))->whereNotNull('completed_at')->count();
                        if ($done >= $vis)
                            $completedProgramsCount++;
                    }
                }
                if ($completedProgramsCount >= $data['value'])
                    $unlocked = true;
            }

            if ($unlocked) {
                $exists = $this->achievements()->where('achievement_id', $id)->exists();
                if (!$exists) {
                    $this->achievements()->create([
                        'achievement_id' => $id,
                        'unlocked_at' => now(),
                    ]);

                    $this->notify(new \App\Notifications\ProgramUpdateNotification([
                        'type' => 'achievement',
                        'message' => "Congratulations! You've unlocked the '{$data['name']}' medal! 🏆 Check your vault!",
                        'achievement_id' => $id
                    ]));
                }
            }
        }
    }

    /**
     * Update activity streak
     */
    public function updateStreak()
    {
        $lastActivity = $this->last_activity_at ? Carbon::parse($this->last_activity_at) : null;
        $today = Carbon::today();

        if (!$lastActivity) {
            $this->streak = 1;
        } elseif ($lastActivity->isYesterday()) {
            $this->streak += 1;
        } elseif (!$lastActivity->isToday()) {
            $this->streak = 1;
        }

        $this->last_activity_at = now();
        $this->save();

        $this->checkAchievements();
    }

    /**
     * Check if child has premium access (active sub or trial)
     */
    public function hasPremiumAccess()
    {
        if (!$this->hasRole('Child')) {
            return true;
        }

        if ($this->premium_status === 'active' && $this->premium_ends_at && $this->premium_ends_at->isFuture()) {
            return true;
        }

        if ($this->premium_status === 'trial' && $this->trial_ends_at && $this->trial_ends_at->isFuture()) {
            return true;
        }

        return false;
    }

    /**
     * Get computed premium status (re-evaluates date)
     */
    public function getComputedPremiumStatusAttribute()
    {
        if (!$this->hasRole('Child')) return 'none';

        if ($this->premium_status === 'active') {
            return ($this->premium_ends_at && $this->premium_ends_at->isPast()) ? 'expired' : 'active';
        }

        if ($this->premium_status === 'trial') {
            return ($this->trial_ends_at && $this->trial_ends_at->isPast()) ? 'expired' : 'trial';
        }

        return $this->premium_status ?: 'none';
    }

    /**
     * Start a trial for this child based on settings
     */
    public function startPremiumTrial()
    {
        $trialDays = (int) (\App\Models\Setting::where('key', 'trial_duration_days')->value('value') ?? 14);
        
        $this->premium_status = 'trial';
        $this->trial_ends_at = now()->addDays($trialDays);
        $this->save();
    }
}
