<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Resources\Comment as CommentResource;
use Illuminate\Http\Request;
use App\Events\CommentEvent;
use App\Task;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CommentResource::collection(Comment::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $comment = $request->isMethod('post') ? New Comment : Comment::findOrFail($request->comment_id);
        $comment->id = $request->input('comment_id');
        $comment->content = $request->input('content');
        $comment->task_id = $request->input('task_id');
        $comment->user_id = $request->input('user_id');
        if($comment->save()) {
            event(new CommentEvent('Comments Updated!',Task::findOrFail($comment->task_id))); 
            return new CommentResource($comment);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if($comment->delete()) {
            return new CommentResource($comment);            
        }
    }
}
