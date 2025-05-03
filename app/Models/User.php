<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use  HasFactory, Notifiable;

    /**
     * الصفات التي يمكن تعيينها بشكل جماعي
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'bio',
        'profile_picture',
        'points',
        'rank',
        'is_admin',
        'is_judge',
    ];

    /**
     * الصفات التي يجب إخفاؤها في المصفوفات
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * الصفات التي يجب تحويلها إلى أنواع أصلية
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_judge' => 'boolean',
    ];

    /**
     * علاقة مع مواضيع المنتدى
     */
    public function forumTopics()
    {
        return $this->hasMany(ForumTopic::class, 'user_id');
    }

    /**
     * علاقة مع ردود المنتدى
     */
    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class, 'user_id');
    }

    /**
     * علاقة مع ردود على الردود في المنتدى
     */
    public function forumReplies()
    {
        return $this->hasMany(ForumReply::class, 'user_id');
    }

    /**
     * علاقة مع مشاركات المنافسات
     */
    public function competitionSubmissions()
    {
        return $this->hasMany(CompetitionSubmission::class, 'user_id');
    }

    
    /**
     * تحديث رتبة المستخدم بناءً على النقاط
     */
    public function updateRank()
    {
        $points = $this->points;
        
        // تحديد الرتبة بناءً على النقاط
        if ($points >= 1000) {
            $this->rank = 'خبير';
        } elseif ($points >= 500) {
            $this->rank = 'محترف';
        } elseif ($points >= 200) {
            $this->rank = 'متقدم';
        } elseif ($points >= 50) {
            $this->rank = 'متوسط';
        } else {
            $this->rank = 'مبتدئ';
        }
        
        $this->save();
    }





    public function topics()
    {
        return $this->hasMany(ForumTopic::class);
    }
    
    /**
     * Get the posts created by the user.
     */
    public function posts()
    {
        return $this->hasMany(ForumPost::class);
    }
    
  
    
    /**
     * Get the competition rankings for the user.
     */
    public function competitionRankings()
    {
        return $this->hasMany(CompetitionRanking::class);
    }
    
    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    





    //////////////////

    public function submissions()
    {
        return $this->hasMany(CompetitionSubmission::class);
    }

    /**
     * Get all rankings of the user.
     */
    public function rankings()
    {
        return $this->hasMany(CompetitionRanking::class);
    }



    /**
     * Check if user is a judge.
     */
    public function isJudge()
    {
        return $this->is_judge;
    }

    /**
     * Count submissions made today for a specific competition.
     */
    public function submissionsToday($competition)
    {
        $today = Carbon::today();
        
        return $this->submissions()
            ->where('competition_id', $competition->id)
            ->whereDate('created_at', $today)
            ->count();
    }

    /**
     * Get user's best submission for a competition.
     */
    public function getBestSubmission($competitionId)
    {
        return $this->submissions()
            ->where('competition_id', $competitionId)
            ->whereNotNull('final_score')
            ->orderBy('final_score', 'desc')
            ->first();
    }

    /**
     * Get user's rank in a competition.
     */
    public function getRankInCompetition($competitionId)
    {
        $ranking = $this->rankings()
            ->where('competition_id', $competitionId)
            ->first();
            
        return $ranking ? $ranking->rank : null;
    }

    /**
     * Update user's global rank based on total points.
     */
    public function updateGlobalRank()
    {
        // Get all users ordered by points
        $users = User::orderBy('points', 'desc')->get();
        
        $rank = 1;
        $lastPoints = null;
        $lastRank = 1;
        
        foreach ($users as $user) {
            // Handle tie points (same rank)
            if ($lastPoints !== null && $user->points === $lastPoints) {
                $user->rank = $lastRank;
            } else {
                $user->rank = $rank;
                $lastRank = $rank;
                $lastPoints = $user->points;
            }
            
            $user->save();
            $rank++;
        }
    }
}