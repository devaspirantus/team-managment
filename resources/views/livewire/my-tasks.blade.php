<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto space-y-6">
    
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">My Assigned Tasks</h1>
        <p class="text-sm text-gray-500">Review your tasks and update their status once completed.</p>
    </div>

    <!-- Success Alert -->
    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Task Cards List -->
    <div class="space-y-4">
        @forelse($myTasks as $task)
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 transition hover:shadow-md">
                
                <!-- Task Details -->
                <div class="space-y-2 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-bold text-gray-900 text-base">{{ $task->title }}</h3>
                        <span class="px-2.5 py-0.5 text-xs font-bold rounded-full 
                            @if($task->task_status === 'done') bg-emerald-100 text-emerald-800 
                            @elseif($task->task_status === 'closed') bg-gray-100 text-gray-700 
                            @else bg-amber-100 text-amber-800 @endif">
                            {{ strtoupper($task->task_status) }}
                        </span>
                    </div>

                    <div class="text-xs text-gray-500 flex flex-wrap gap-x-4 gap-y-1">
                        <p><strong>Due Date:</strong> <span class="text-gray-700 font-medium">{{ $task->due_date?->format('Y-m-d') }}</span></p>
                        <p><strong>Duration:</strong> <span class="text-gray-700 font-medium">{{ $task->due_duration ?? 'N/A' }}</span></p>
                        @if($task->finished_date)
                            <p class="text-emerald-600 font-medium"><strong>Finished At:</strong> {{ $task->finished_date->format('Y-m-d H:i') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Action Button -->
                <div class="w-full sm:w-auto flex-shrink-0">
                    @if($task->task_status !== 'done' && $task->task_status !== 'closed')
                        <button wire:click="markAsDone({{ $task->id }})" class="w-full sm:w-auto px-6 h-11 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center justify-center">
                            Mark as Done
                        </button>
                    @endif
                </div>

            </div>
        @empty
            <div class="bg-white p-8 rounded-2xl text-center border border-gray-100 text-gray-500">
                No tasks assigned to you right now. Great job!
            </div>
        @endforelse
    </div>
</div>