<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class MyTasks extends Component
{
    public function markAsDone($taskId)
    {
        $task = Task::where('id', $taskId)
                    ->where('assigned_to', Auth::id())
                    ->firstOrFail();

        $now = Carbon::now();
        
        // نهاية يوم الاستحقاق (أو قارن بالوقت المحدد)
        $dueDateEnd = Carbon::parse($task->due_date)->endOfDay();

        // تحديد الحالة: Ontime أو Delay
        $statusPerf = $now->greaterThan($dueDateEnd) ? 'Delay' : 'Ontime';

        // حساب الوقت المستغرق بالدقائق (الفارق بين وقت الإنشاء والآن)
        $timingMinutes = (int) $task->created_at->diffInMinutes($now);

        $task->update([
            'task_status'    => 'done',
            'finished_date'  => $now,
            'timing_minutes' => $timingMinutes,
            'stats'          => $statusPerf,
        ]);

        session()->flash('success', "Task marked as Done! Performance: {$statusPerf}");
    }

    public function render()
    {
        return view('livewire.my-tasks', [
            'myTasks' => Task::where('assigned_to', Auth::id())->latest()->get(),
        ]);
    }
}