<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $fillable = ['user_id', 'topic_id', 'title', 'summary', 'key_points'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}