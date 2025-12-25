<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Teams: {{ $project->name }}</h2>
            <a href="{{ route('projects.teams.create', $project) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Create Team</a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))<div class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>@endif
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                @if($teams->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($teams as $team)
                            <a href="{{ route('teams.show', $team) }}" class="block p-6 border rounded hover:shadow-lg">
                                <h3 class="font-semibold text-lg">{{ $team->name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ Str::limit($team->description, 60) }}</p>
                                <div class="mt-4 text-xs text-gray-500">{{ $team->members->count() }} members</div>
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-6">{{ $teams->links() }}</div>
                @else
                    <p class="text-center text-gray-500 py-8">No teams yet. Create one to organize your workspace!</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
