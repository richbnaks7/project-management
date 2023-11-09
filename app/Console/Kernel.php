<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Project;
use App\Notifications\ProjectDeadlineReminder;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $projects = Project::where('deadline', '<', now()->addMinutes(10))
                ->where('deadline', '>', now())
                ->get();
    
            foreach ($projects as $project) {
                foreach ($project->users as $user) {
                    $user->notify(new ProjectDeadlineReminder($project));
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
