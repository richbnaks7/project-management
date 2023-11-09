<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;
use App\Models\User;

class TaskAssigned
{
    use Dispatchable, SerializesModels;

    public $task;
    public $assignedUser;

    /**
     * Create a new event instance.
     */
    public function __construct(Task $task, User $assignedUser)
    {
        $this->task = $task;
        $this->assignedUser = $assignedUser;
    }
}
