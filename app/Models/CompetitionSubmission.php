<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'user_id',
        'title',
        'description',
        'file_path',
        'original_filename',
        'github_url',
        'accuracy_score',
        'performance_score',
        'final_score',
        'feedback',
        'evaluated_by',
        'evaluated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'evaluated_at' => 'datetime',
        'accuracy_score' => 'float',
        'performance_score' => 'float',
        'final_score' => 'float',
    ];

    /**
     * Get the competition that owns the submission.
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Get the user that owns the submission.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the user who evaluated the submission.
     */
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
    
    /**
     * Get submission status.
     */
    public function getStatusAttribute()
    {
        if ($this->final_score !== null) {
            return 'evaluated';
        }
        
        return 'pending';
    }
    
    /**
     * Get evaluation category based on score.
     */
    public function getEvaluationCategoryAttribute()
    {
        if ($this->final_score === null) {
            return 'pending';
        }
        
        if ($this->final_score >= 80) {
            return 'excellent';
        } elseif ($this->final_score >= 60) {
            return 'good';
        } elseif ($this->final_score >= 40) {
            return 'average';
        } else {
            return 'below_average';
        }
    }
    
    /**
     * Scope a query to only include pending submissions.
     */
    public function scopePending($query)
    {
        return $query->whereNull('final_score');
    }
    
    /**
     * Scope a query to only include evaluated submissions.
     */
    public function scopeEvaluated($query)
    {
        return $query->whereNotNull('final_score');
    }
}