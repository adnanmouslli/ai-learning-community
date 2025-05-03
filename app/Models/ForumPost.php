<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    public function topic()
{
    return $this->belongsTo(ForumTopic::class, 'topic_id');
}

public function user()
{
    return $this->belongsTo(User::class);
}

public function replies()
{
    return $this->hasMany(ForumReply::class, 'post_id');
}
}
