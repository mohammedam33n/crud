<?php

namespace App\ViewModel;

use App\Models\Task;
use App\Models\User;
use Spatie\ViewModels\ViewModel;

class TaskViewModel extends ViewModel
{

    public array $users;
    public Task $task;

    public function __construct($task = null)
    {
        $this->task = is_null($task)  ? new Task(old()) : $task;
        $this->users = User::select('id', 'name')->pluck('name', 'id')->toArray();
    }

    public function action(): string
    {
        return is_null($this->task->id)
            ? route('tasks.store')
            : route('tasks.update', ['task' => $this->task->id]);
    }

    public function method(): string
    {
        return is_null($this->task->id) ? 'POST' : 'PUT';
    }

}
