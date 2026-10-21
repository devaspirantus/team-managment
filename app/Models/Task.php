<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'assigned_to', 'created_by', 'due_duration', 'due_date', 'finished_date', 'task_status', 'timing_minutes', 'stats'])]

class Task extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'finished_date' => 'datetime',
        ];
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
