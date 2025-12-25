<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manage Members: {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))<div class="px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded">{{ session('error') }}</div>@endif

            <!-- Add Member Form -->
            @can('addMember', $project)
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Add New Member</h3>
                    <form method="POST" action="{{ route('projects.addMember', $project) }}" class="flex space-x-4">
                        @csrf
                        <div class="flex-1">
                            <input type="email" name="email" placeholder="Email address" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div>
                            <select name="role" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="member">Member</option>
                                <option value="manager">Manager</option>
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Add Member</button>
                    </form>
                </div>
            @endcan

            <!-- Members List -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Team Members ({{ $members->count() }})</h3>
                <div class="space-y-3">
                    @foreach($members as $member)
                        <div class="flex justify-between items-center p-4 border rounded">
                            <div class="flex-1">
                                <div class="font-medium">{{ $member->name }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $member->email }}</div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="px-3 py-1 text-xs rounded-full
                                    @if($member->pivot->role === 'owner') bg-purple-100 text-purple-800
                                    @elseif($member->pivot->role === 'manager') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($member->pivot->role) }}
                                </span>
                                @if($member->pivot->role !== 'owner')
                                    @can('removeMember', [$project, $member])
                                        <form method="POST" action="{{ route('projects.removeMember', [$project, $member->id]) }}" onsubmit="return confirm('Remove this member?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                        </form>
                                    @endcan
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('projects.show', $project) }}" class="text-indigo-600 hover:text-indigo-800">← Back to Workspace</a>
            </div>
        </div>
    </div>
</x-app-layout>
