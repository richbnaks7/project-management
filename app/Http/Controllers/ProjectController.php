<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectController extends Controller
{
    public function index()
    {
        $totalProjects = Project::count();
        $totalTasks = Task::count();
        $totalCompletedTasks = Task::where('status', 'completed')->count();

        $projects = Project::paginate(10);

        return [
            'total_projects' => $totalProjects,
            'total_tasks' => $totalTasks,
            'total_completed_tasks' => $totalCompletedTasks,
            'projects' => JsonResource::collection($projects),
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'status' => 'required|in:pending,approved,rejected',
            'deadline' => 'required|date',
        ]);

        $project = Project::create($request->all());
        return new ProjectResource($project);
    }

    public function update(Request $request, string $id){
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'status' => 'required|in:pending,approved,rejected',
            'deadline' => 'required|date',
        ]);

        $project = Project::findOrFail($id);
        $project->update($request->all());
        return new ProjectResource($project);
    }

    public function show(string $id)
    {
        $project = Project::findOrFail($id);
        return new ProjectResource($project);
    }

    public function destroy(string $id){
        $project = Project::findOrFail($id);
        $project->delete();
        return new ProjectResource($project);
    }
    
}
