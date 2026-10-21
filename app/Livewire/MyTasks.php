<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')] // <-- أضف هذه السمة هنا
class MyTasks extends Component
{
    public function markAsDone($taskId)
    {
        $task = Task::where('id', $taskId)
                    ->where('assigned_to', Auth::id())
                    ->firstOrFail();

        $task->update([
            'task_status' => 'done',
            'finished_date' => Carbon::now(),
        ]);

        session()->flash('success', 'Task marked as Done!');
    }

    public function render()
    {
        return view('livewire.my-tasks', [
            'myTasks' => Task::where('assigned_to', Auth::id())->latest()->get(),
        ]);
    }
}