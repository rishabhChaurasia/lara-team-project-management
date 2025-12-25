<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Tasks: {{ $project->name }}</h2>
            <a href="{{ route('projects.tasks.create', $project) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Create Task</a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))<div class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>@endif
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                @if($tasks->count() > 0)
                    <div class="space-y-3">
                        @foreach($tasks as $task)
                            <a href="{{ route('projects.tasks.show', $task) }}" class="block p-4 border rounded hover:bg-gray-50 dark:hover:bg-gray-700">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="font-semibold">{{ $task->title }}</h3>
                                        @if($task->description)<p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($task->description, 100) }}</p>@endif
                                        <div class="flex space-x-4 mt-2 text-xs text-gray-500">
                                            @if($task->assignee)<span>👤 {{ $task->assignee->name }}</span>@endif
                                            @if($task->due_date)<span>📅 {{ $task->due_date->format('M d') }}</span>@endif
                                            @if($task->priority)<span class="px-2 py-1 rounded {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">{{ ucfirst($task->priority) }}</span>@endif
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 text-xs rounded-full bg-gray-200 dark:bg-gray-600">{{ ucfirst($task->status) }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">No tasks yet. Create one to get started!</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
