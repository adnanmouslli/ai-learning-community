<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumTopic extends Model
{
    public function category()
{
    return $this->belongsTo(ForumCategory::class, 'category_id');
}

public function user()
{
    return $this->belongsTo(User::class);
}

public function posts()
{
    return $this->hasMany(ForumPost::class, 'topic_id');
}
}
