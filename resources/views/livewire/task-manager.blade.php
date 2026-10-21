<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6 font-sans text-gray-900 bg-gray-50/50 min-h-screen">
    
    <!-- DGA Styled Header & Actions Bar -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 leading-snug">Task Management</h1>
            <p class="text-sm text-gray-600 mt-1">Assign tasks to team members and track performance effortlessly.</p>
        </div>

        <!-- Excel Actions (DGA Compliant Buttons) -->
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tasks.export') }}" class="px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Export Excel</span>
            </a>

            <form action="{{ route('admin.tasks.import') }}" method="POST" enctype="multipart/form-data" class="m-0">
                @csrf
                <label class="cursor-pointer px-4 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-900 border border-indigo-200 text-xs font-bold rounded-xl transition flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span>Import Excel</span>
                    <input type="file" name="excel_file" required class="hidden" onchange="this.form.submit()">
                </label>
            </form>
        </div>
    </div>

    <!-- Alert Notification -->
    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-300 text-emerald-900 rounded-xl text-sm font-semibold shadow-xs">
            {{ session('success') }}
        </div>
    @endif

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left: Form Card -->
        <div class="lg:col-span-1 bg-white p-6 rounded-2xl border border-gray-200 shadow-xs space-y-5 sticky top-6">
            <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Assign New Task</h2>

            <form wire:submit.prevent="createTask" class="space-y-4">
                <div>
                    <label for="title" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">To-Do Title</label>
                    <input type="text" id="title" wire:model="title" class="w-full h-11 px-3.5 rounded-xl border-gray-300 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-sm font-medium" placeholder="e.g. Design API architecture" required>
                    @error('title') <span class="text-red-600 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="assigned_to" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Assigned To</label>
                    <select id="assigned_to" wire:model="assigned_to" class="w-full h-11 px-3.5 rounded-xl border-gray-300 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-sm font-medium bg-white" required>
                        <option value="">Select Team Member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_to') <span class="text-red-600 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="due_duration" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Due (Duration)</label>
                    <input type="text" id="due_duration" wire:model="due_duration" class="w-full h-11 px-3.5 rounded-xl border-gray-300 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-sm font-medium" placeholder="e.g. 2 Days, 4 Hours">
                    @error('due_duration') <span class="text-red-600 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="due_date" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Due Date</label>
                    <input type="date" id="due_date" wire:model="due_date" class="w-full h-11 px-3.5 rounded-xl border-gray-300 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-sm font-medium" required>
                    @error('due_date') <span class="text-red-600 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full h-11 bg-indigo-800 hover:bg-indigo-900 text-white font-bold rounded-xl text-sm transition shadow-xs">
                    Assign Task
                </button>
            </form>
        </div>

        <!-- Right: Tasks List Cards -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between px-1">
                <h3 class="text-base font-bold text-gray-900">Tasks List</h3>
                <span class="text-xs font-bold bg-gray-200 text-gray-800 px-3 py-1 rounded-full">Total: {{ $tasks->total() }}</span>
            </div>

            <div class="space-y-4">
                @forelse($tasks as $task)
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs space-y-3 text-left">
                        
                        <!-- Top Row: Title, Status Badges -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-3">
                            <h4 class="font-bold text-gray-900 text-base leading-snug">{{ $task->title }}</h4>
                            
                            <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
                                <!-- Status Badge -->
                                <span class="px-2.5 py-1 text-xs font-bold rounded-md uppercase tracking-wider
                                    @if($task->task_status === 'done') bg-emerald-100 text-emerald-900 border border-emerald-200
                                    @elseif($task->task_status === 'closed') bg-gray-100 text-gray-800 border border-gray-200
                                    @else bg-amber-100 text-amber-900 border border-amber-200 @endif">
                                    {{ $task->task_status }}
                                </span>

                                <!-- Performance Badge (Ontime / Delay) -->
                                @if($task->stats)
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-md uppercase tracking-wider
                                        @if($task->stats === 'Ontime') bg-blue-100 text-blue-900 border border-blue-200
                                        @else bg-rose-100 text-rose-900 border border-rose-200 @endif">
                                        ⏱️ {{ $task->stats }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Details Block (Clean Structured Rows) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-gray-600 font-medium pt-1">
                            <div>
                                <span class="text-gray-500">Assigned To:</span> 
                                <span class="text-gray-900 font-bold ml-1">{{ $task->assignedUser->name ?? 'Unassigned' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Due Date:</span> 
                                <span class="text-gray-900 font-bold ml-1">{{ $task->due_date?->format('Y-m-d') }} ({{ $task->due_duration ?? 'N/A' }})</span>
                            </div>
                            @if($task->finished_date)
                                <div class="sm:col-span-2 text-emerald-800 font-bold mt-1">
                                    <span>Finished Date:</span> {{ $task->finished_date->format('Y-m-d H:i') }}
                                </div>
                            @endif
                        </div>

                        <!-- Action Button -->
                        @if($task->task_status === 'done')
                            <div class="pt-2 border-t border-gray-100 flex justify-end">
                                <button wire:click="closeTask({{ $task->id }})" class="px-4 py-2 bg-gray-900 hover:bg-black text-white text-xs font-bold rounded-xl transition shadow-xs">
                                    Close Task
                                </button>
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="bg-white p-8 rounded-2xl border border-gray-200 text-center text-gray-500 text-sm font-medium">
                        No tasks created yet. Use the form on the left to assign your first task!
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="pt-2">
                {{ $tasks->links() }}
            </div>
        </div>

    </div>
</div>