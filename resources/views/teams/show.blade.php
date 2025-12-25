<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">{{ $team->name }}</h2>
            @can('update', $team)<a href="{{ route('teams.edit', $team) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Edit</a>@endcan
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))<div class="px-4 py-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="px-4 py-3 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>@endif
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-2">Description</h3>
                <p class="text-gray-700 dark:text-gray-300">{{ $team->description ?? 'No description provided.' }}</p>
            </div>
            @can('addMember', $team)
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold mb-4">Add Member</h3>
                    <form method="POST" action="{{ route('teams.addMember', $team) }}" class="flex space-x-4">
                        @csrf
                        <input type="email" name="email" placeholder="Email address" required class="flex-1 rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Add</button>
                    </form>
                </div>
            @endcan
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-4">Members ({{ $team->members->count() }})</h3>
                <div class="space-y-2">
                    @forelse($team->members as $member)
                        <div class="flex justify-between items-center p-3 border rounded">
                            <div><div class="font-medium">{{ $member->name }}</div><div class="text-sm text-gray-600">{{ $member->email }}</div></div>
                            @can('removeMember', [$team, $member])
                                <form method="POST" action="{{ route('teams.removeMember', [$team, $member->id]) }}" onsubmit="return confirm('Remove?');">@csrf @method('DELETE')<button class="text-red-600 text-sm">Remove</button></form>
                            @endcan
                        </div>
                    @empty
                        <p class="text-gray-500">No members yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
