<?php

namespace App\Exports;

use App\Models\Task;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TasksExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Collection
    {
        return Task::with(['assignedUser', 'creator'])->latest()->get();
    }
    public function headings(): array
    {
        return [
            'Task ID',
            'To-Do Title',
            'Assigned To',
            'Assigned By',
            'Due Duration',
            'Due Date',
            'Status',
            'Finished Date',
            'Timing (Mins)',
            'Stats',
        ];
    }

    public function map($task): array
    {
        return [
       $task->id,
        $task->title,
        $task->assignedUser->name ?? 'Unassigned',
        $task->creator->name ?? 'System',
        $task->due_duration ?? 'N/A',
        $task->due_date?->format('Y-m-d'),
        strtoupper($task->task_status),
        $task->finished_date?->format('Y-m-d H:i'),
        $task->timing_minutes ? $task->timing_minutes . ' Mins' : 'N/A',
        $task->stats ?? 'Pending', // يُظهر Ontime أو Delay
        ];
    }
}
