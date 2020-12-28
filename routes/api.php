<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// list tasks
Route::get('/tasks', 'TaskController@index');

// get a single task
Route::get('/tasks/{id}', 'TaskController@show');

// update a task
Route::put('/tasks', 'TaskController@store');

// delete a task
Route::delete('/tasks/{id}', 'TaskController@destroy');

// create a task
Route::post('/tasks', 'TaskController@store');

// create a comment
Route::post('/comments', 'CommentController@store');

// delete a comment
Route::delete('/comments/{id}', 'CommentController@destroy');

// update a comment
Route::put('/comments', 'CommentController@store');