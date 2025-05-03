<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionRanking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $attributes = [
        'rank' => 999, // Default high value that will be updated later
    ];

    protected $fillable = [
        'competition_id',
        'user_id',
        'score',
        'rank'
    ];

    /**
     * Get the competition that the ranking belongs to.
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Get the user that the ranking belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}