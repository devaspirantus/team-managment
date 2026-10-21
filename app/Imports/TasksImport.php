<?php

namespace App\Imports;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class TasksImport implements ToModel, WithHeadingRow
{
    public function model(array $row): Model|null
    {
     $user = User::where('name', $row['assigned_to'] ?? '')->first();

        return new Task([
            'title'        => $row['to_do_title'] ?? $row['title'],
            'assigned_to'  => $user?->id ?? Auth::id(),
            'created_by'   => Auth::id(),
            'due_duration' => $row['due_duration'] ?? '1 Day',
            'due_date'     => $row['due_date'] ?? now()->addDays(2)->format('Y-m-d'),
            'task_status'  => strtolower($row['status'] ?? 'pending'),
        ]);
    }
}
