<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use App\Events\TaskAssigned;
use App\Models\User;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'status' => 'required|in:pending,approved,rejected',
            'project_id' => 'required',
        ]);

        $user = Auth::user();
        $setUser = (!$request->user_id) ? $user->id : $request->user_id;

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $setUser,
            'project_id' => $request->project_id
        ]);

        if($user !== $setUser){
            $assignedUser = User::find($setUser);
            event(new TaskAssigned($task, $user));
        }

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tasks = Task::findOrFail($id);
        return new TaskResource($tasks);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'project_id' => 'required',
            'status' => 'required|in:pending,approved,rejected',
            'project_id' => 'required',
        ]);

        $user = Auth::user();
        $setUser = (!$request->user_id) ? $user->id : $request->user_id;

        $task = Task::findOrFail($id);

        if($task->user_id !== $setUser){
            $assignedUser = User::find($task->user_id);
            event(new TaskAssigned($task, $assignedUser));
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $setUser,
            'project_id' => $request->project_id
        ]);

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return new TaskResource($task);
    }
}