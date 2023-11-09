<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Project;

class CheckProjectDeadline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $projectsToUpdate = Project::where('status', 'pending')
            ->where('deadline', '<', now())
            ->get();

        // Mark projects as completed
        foreach ($projectsToUpdate as $project) {
            $project->update(['status' => 'completed']);
            $message = "The deadline for this project has passed. It has been marked as completed.";

            return Response(['message' => $message], 200);
        }

        return $next($request);
    }
}