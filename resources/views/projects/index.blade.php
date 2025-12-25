<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Workspaces') }}
            </h2>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700">
                Create New Workspace
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($projects->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($projects as $project)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hover:shadow-lg transition">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-lg font-semibold">{{ $project->name }}</h3>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($project->status === 'active') bg-green-100 text-green-800
                                            @elseif($project->status === 'completed') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        {{ Str::limit($project->description, 80) }}
                                    </p>
                                    
                                    <div class="text-xs text-gray-500 mb-4">
                                        <p>Created by: {{ $project->creator->name }}</p>
                                        <p>Created: {{ $project->created_at->diffForHumans() }}</p>
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <a href="{{ route('projects.show', $project) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            View →
                                        </a>
                                        @can('update', $project)
                                            <a href="{{ route('projects.edit', $project) }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                                                Edit
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $projects->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500 mb-4">No workspaces found. Create your first one to get started!</p>
                            <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Create Workspace
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
