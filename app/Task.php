<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * each task can have many users
     */
    public function users()
    {
        // return $this->belongsToMany('App\User', 'task_user', 'user_id');
        return $this->belongsToMany('App\User','task_user');

    }


    /**
     * each task can have many comments
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * each task has its owner. created by
     */
    public function created_by()
    {
        return $this->belongsTo('App\User', 'users', 'id');
    }
}
