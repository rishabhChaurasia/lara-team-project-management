<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $project->name }}
            </h2>
            <div class="flex space-x-2">
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Edit</a>
                @endcan
                <a href="{{ route('projects.members', $project) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Manage Members</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <!-- Project Details -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Workspace Details</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><dt class="font-medium text-gray-500">Status</dt><dd class="mt-1 text-gray-900 dark:text-gray-100">{{ ucfirst($project->status) }}</dd></div>
                        <div><dt class="font-medium text-gray-500">Created By</dt><dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $project->creator->name }}</dd></div>
                        <div><dt class="font-medium text-gray-500">Start Date</dt><dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $project->start_date ? $project->start_date->format('M d, Y') : 'Not set' }}</dd></div>
                        <div><dt class="font-medium text-gray-500">End Date</dt><dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $project->end_date ? $project->end_date->format('M d, Y') : 'Not set' }}</dd></div>
                    </dl>
                    @if($project->description)
                        <div class="mt-4"><dt class="font-medium text-gray-500">Description</dt><dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $project->description }}</dd></div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="text-3xl font-bold text-indigo-600">{{ $project->members->count() }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Team Members</div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="text-3xl font-bold text-green-600">{{ $project->teams->count() }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Teams</div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="text-3xl font-bold text-blue-600">{{ $project->tasks->count() }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Tasks</div>
                </div>
            </div>

            <!-- Teams Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Teams</h3>
                        <a href="{{ route('projects.teams.create', $project) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">Create Team</a>
                    </div>
                    @if($project->teams->count() > 0)
                        <div class="space-y-2">
                            @foreach($project->teams as $team)
                                <a href="{{ route('teams.show', $team) }}" class="block p-4 border rounded hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <div class="font-medium">{{ $team->name }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $team->members->count() }} members</div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No teams yet. Create one to organize your workspace.</p>
                    @endif
                </div>
            </div>

            <!-- Recent Tasks -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Recent Tasks</h3>
                        <a href="{{ route('projects.tasks.create', $project) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">Create Task</a>
                    </div>
                    @if($project->tasks->count() > 0)
                        <div class="space-y-2">
                            @foreach($project->tasks->take(5) as $task)
                                <a href="{{ route('projects.tasks.show', $task) }}" class="block p-4 border rounded hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <div class="flex justify-between">
                                        <div class="font-medium">{{ $task->title }}</div>
                                        <span class="text-xs px-2 py-1 rounded bg-gray-200 dark:bg-gray-600">{{ ucfirst($task->status) }}</span>
                                    </div>
                                    @if($task->assignee)
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Assigned to: {{ $task->assignee->name }}</div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('projects.tasks.index', $project) }}" class="text-indigo-600 hover:text-indigo-800">View all tasks →</a>
                        </div>
                    @else
                        <p class="text-gray-500">No tasks yet. Create one to get started.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
