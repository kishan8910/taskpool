<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



     /**
     * each user can have more than one comment
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * each task can have more than one user assigned in the pivot table task_user
     */
    public function tasks()
    {
        // return $this->belongsToMany('App\Task', 'task_user', 'task_id');
        return $this->belongsToMany('App\Task','task_user');

    }


     /**
     * each user can have more than one tasks
     */
    public function created_by()
    {
        return $this->hasMany('App\Task', 'tasks', 'created_by');
    }



}
