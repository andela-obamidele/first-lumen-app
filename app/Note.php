<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'user_id'
    ];
    
    /**
    * Note belongs to User
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function users()
    {
        return $this->belongsTo('App\User');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'note_tags')
            ->withTimestamps();
    }
}
