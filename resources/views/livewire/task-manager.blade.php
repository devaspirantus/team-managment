<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Task Management</h1>
            <p class="text-sm text-gray-500">Assign tasks to team members and track performance seamlessly.</p>
        </div>
    </div>

    <!-- Success Alert -->
    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Main Layout Grid (Mobile: Stacked, Desktop: 1/3 Form & 2/3 List) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Create Task Form (Sticky on Desktop) -->
        <div class="lg:col-span-1">
            <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-gray-100 lg:sticky lg:top-6 space-y-4">
                <h2 class="text-lg font-bold text-gray-900 border-b pb-3">Assign New Task</h2>

                <form wire:submit.prevent="createTask" class="space-y-4">
                    <div>
                        <label for="title" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">To-Do Title</label>
                        <input type="text" id="title" wire:model="title" class="w-full h-12 px-4 rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="e.g. Design API architecture" required>
                        @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="assigned_to" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Assigned To</label>
                        <select id="assigned_to" wire:model="assigned_to" class="w-full h-12 px-4 rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500 text-sm bg-white" required>
                            <option value="">Select Team Member</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                        @error('assigned_to') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="due_duration" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Due (Duration)</label>
                        <input type="text" id="due_duration" wire:model="due_duration" class="w-full h-12 px-4 rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="e.g. 2 Days, 4 Hours">
                        @error('due_duration') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="due_date" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Due Date</label>
                        <input type="date" id="due_date" wire:model="due_date" class="w-full h-12 px-4 rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500 text-sm" required>
                        @error('due_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full h-12 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition duration-150 flex items-center justify-center shadow-sm">
                        Assign Task
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Tasks List & Table (Responsive Card/Table View) -->
        <div class="lg:col-span-2 space-y-4">
            <h3 class="text-lg font-bold text-gray-900">Team Performance & Tasks</h3>

            <div class="space-y-3">
                @forelse($tasks as $task)
                    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 transition hover:shadow-md">
                        
                        <!-- Task Info -->
                        <div class="space-y-1.5 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="font-bold text-gray-900 text-base">{{ $task->title }}</h4>
                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full 
                                    @if($task->task_status === 'done') bg-emerald-100 text-emerald-800 
                                    @elseif($task->task_status === 'closed') bg-gray-100 text-gray-700 
                                    @else bg-amber-100 text-amber-800 @endif">
                                    {{ strtoupper($task->task_status) }}
                                </span>
                            </div>

                            <div class="text-xs text-gray-500 flex flex-wrap gap-x-4 gap-y-1">
                                <p><strong>Assigned:</strong> <span class="text-gray-700 font-medium">{{ $task->assignedUser->name ?? 'Unassigned' }}</span></p>
                                <p><strong>Due:</strong> <span class="text-gray-700 font-medium">{{ $task->due_date?->format('Y-m-d') }} ({{ $task->due_duration }})</span></p>
                                @if($task->finished_date)
                                    <p class="text-emerald-600"><strong>Finished:</strong> {{ $task->finished_date->format('Y-m-d H:i') }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="w-full sm:w-auto flex-shrink-0">
                            @if($task->task_status === 'done')
                                <button wire:click="closeTask({{ $task->id }})" class="w-full sm:w-auto px-4 h-10 bg-gray-900 hover:bg-gray-800 text-white text-xs font-bold rounded-xl transition">
                                    Close Task
                                </button>
                            @endif
                        </div>

                    </div>
                @empty
                    <div class="bg-white p-8 rounded-2xl text-center border border-gray-100 text-gray-500">
                        No tasks created yet. Use the form to assign your first task!
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="pt-4">
                {{ $tasks->links() }}
            </div>
        </div>

    </div>
</div>