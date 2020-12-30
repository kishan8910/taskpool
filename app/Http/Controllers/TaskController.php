<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
// use App\Http\Requests;
use App\Task;
use App\Http\Resources\Task as TaskResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{

    /**
     * Get a validator for an incoming task request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title' => ['required', 'string', 'max:100', 'unique:tasks'],
            'content' => ['required', 'string']
        ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get tasks
        $tasks = Task::with(['comments','users'])->orderBy('created_at', 'desc')->paginate(3);
        // $tasks = Task::with('users')->paginate(100);

        //return the collection of tasks as a resource
        return TaskResource::collection($tasks);

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
        
        $task = $request->isMethod('put') ? Task::findOrFail($request->task_id) : New Task;
        $task->id = $request->input('task_id');
        $task->title = $request->input('title');
        $task->content = $request->input('content');
        $task->views = $request->input('views');
        $task->created_by = $request->input('created_by');
        
        if($request->hasFile('image')) {
            Storage::delete($task->image);
            $task->image = $request->image->store('images');
        }
        
        if($task->save()) {
            return new TaskResource($task);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new TaskResource(Task::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        if($task->delete()) {
            Storage::delete($task->image);
            return new TaskResource($task);            
        }
    }
}
