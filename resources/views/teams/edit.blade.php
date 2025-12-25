<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Edit Team</h2></x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('teams.update', $team) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Team Name *</label>
                        <input type="text" name="name" value="{{ old('name', $team->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description', $team->description) }}</textarea>
                    </div>
                    <div class="flex justify-between">
                        @can('delete', $team)
                            <form method="POST" action="{{ route('teams.destroy', $team) }}" onsubmit="return confirm('Delete this team?');">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-800">Delete Team</button></form>
                        @endcan
                        <div class="flex space-x-4">
                            <a href="{{ route('teams.show', $team) }}" class="text-gray-600">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
