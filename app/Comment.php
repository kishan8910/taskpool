<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    
    /**
     * each comment will have a task
     */
    public function task()
    {
        return $this->belongsTo('App\Task');
    }


    /**
     * each comment will have a user
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
