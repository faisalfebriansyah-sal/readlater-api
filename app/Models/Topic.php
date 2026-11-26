<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['user_id', 'name'];

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
}