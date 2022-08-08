<?php

namespace App\Http\Livewire;

use App\Models\Task;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Livewire\Component;

class TaskLiveWire extends Component
{
    public array $users;
    public Task $task;
    public  $modelShow = false;
    protected $rules = ['task.name' => 'min:3' , 'task.description'=>'min:6','task.user_id'=>'integer'];

    public function mount(){
        $this->users = User::select('id','name')->pluck('name','id')->toArray();
    }

    public function render()
    {
        return view('livewire.task-live-wire',['tasks' => Task::orderBy('id','DESC')->get()]);
    }

    public function create(){
         $this->modelShow = true;
         $this->task  = new Task(old());
    }

    public function edit($id){
        $this->task = Task::findOrFail($id);
        $this->modelShow = true;
    }

     public function close(){
        $this->modelShow = false;
    }

     public function save(){
          Task::updateOrCreate(['id' => $this->task->id], [
            'name' => $this->task->name,
            'description' => $this->task->description,
            'user_id' => $this->task->user_id,
        ]);
        $this->close();
    }
    
    public function destroy(Task $task ){
        $task->delete();
    }

}
