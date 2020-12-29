<?php

namespace App\Http\Resources;
use App\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;

use App\Http\Resources\Task as TaskResource;
class Comment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'user' => User::all()->find($this->user_id),
            'task_id' => $this->task_id
        ];
    }
}
