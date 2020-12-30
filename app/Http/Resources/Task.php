<?php

namespace App\Http\Resources;
use App\User;

use App\Http\Resources\Comment as CommentResource;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Task extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'views' => $this->views,
            'assignees' => UserResource::collection($this->whenLoaded('users')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'user' => User::all()->find($this->created_by),
            'image' => $this->image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
