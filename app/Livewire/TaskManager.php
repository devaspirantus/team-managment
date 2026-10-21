<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')] // <-- أضف هذه السمة هنا
class TaskManager extends Component
{
    use WithPagination;

    public $title;
    public $assigned_to;
    public $due_duration;
    public $due_date;

    protected $rules = [
        'title' => 'required|string|max:255',
        'assigned_to' => 'required|exists:users,id',
        'due_duration' => 'nullable|string|max:100',
        'due_date' => 'required|date',
    ];

    public function createTask()
    {
        $this->validate();

        Task::create([
            'title' => $this->title,
            'assigned_to' => $this->assigned_to,
            'created_by' => Auth::id(),
            'due_duration' => $this->due_duration,
            'due_date' => $this->due_date,
            'task_status' => 'pending',
        ]);

        $this->reset(['title', 'assigned_to', 'due_duration', 'due_date']);
        session()->flash('success', 'Task created and assigned successfully.');
    }

    public function closeTask($taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->update([
            'task_status' => 'closed',
        ]);

        session()->flash('success', 'Task has been closed successfully.');
    }

    public function render()
    {
        return view('livewire.task-manager', [
            'tasks' => Task::with('assignedUser')->latest()->paginate(10),
            'members' => User::where('role', 'member')->get(),
        ]);
    }
}