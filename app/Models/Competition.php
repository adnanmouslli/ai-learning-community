<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'rules',
        'evaluation_criteria',
        'dataset_description',
        'dataset_url',
        'start_date',
        'end_date',
        'status',
        'max_daily_submissions',
        'is_featured',
        'type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_featured' => 'boolean',
    ];

    /**
     * Get all submissions for this competition.
     */
    public function submissions()
    {
        return $this->hasMany(CompetitionSubmission::class);
    }

    /**
     * Get all rankings for this competition.
     */
    public function rankings()
    {
        return $this->hasMany(CompetitionRanking::class);
    }

    /**
     * Get top three rankings for this competition.
     */
    public function topRankings()
    {
        return $this->rankings()
            ->with('user')
            ->orderBy('rank')
            ->take(3);
    }

    /**
     * Get count of unique participants
     */
    public function getUniqueParticipantsCountAttribute()
    {
        return $this->submissions()
            ->distinct('user_id')
            ->count('user_id');
    }

    /**
     * Get pending submissions count
     */
    public function getPendingSubmissionsCountAttribute()
    {
        return $this->submissions()
            ->whereNull('final_score')
            ->count();
    }

    /**
     * Get evaluated submissions count
     */
    public function getEvaluatedSubmissionsCountAttribute()
    {
        return $this->submissions()
            ->whereNotNull('final_score')
            ->count();
    }

    /**
     * Check if the competition is open for submissions
     */
    public function isOpenForSubmissions()
    {
        return $this->status === 'active';
    }

    /**
     * Get time remaining until competition ends
     */
    public function getTimeRemainingAttribute()
    {
        if ($this->status !== 'active') {
            return null;
        }
        
        return $this->end_date->diffForHumans();
    }

    /**
     * Auto-update status based on dates
     */
    public function updateStatus()
    {
        $now = Carbon::now();
        
        if ($this->start_date->gt($now)) {
            $this->status = 'upcoming';
        } elseif ($this->end_date->lt($now)) {
            $this->status = 'completed';
        } else {
            $this->status = 'active';
        }
        
        return $this->save();
    }

    /**
     * Scope a query to only include upcoming competitions.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    /**
     * Scope a query to only include active competitions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include completed competitions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}