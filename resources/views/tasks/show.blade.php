<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">{{ $task->title }}</h2>
            @can('update', $task)<a href="{{ route('projects.tasks.edit', $task) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Edit</a>@endcan
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))<div class="px-4 py-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>@endif
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-2 gap-4">
                    <div><dt class="text-sm text-gray-500">Status</dt><dd class="mt-1 font-medium">{{ ucfirst($task->status) }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Priority</dt><dd class="mt-1 font-medium">{{ ucfirst($task->priority) }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Assigned To</dt><dd class="mt-1">{{ $task->assignee?->name ?? 'Unassigned' }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Due Date</dt><dd class="mt-1">{{ $task->due_date?->format('M d, Y') ?? 'Not set' }}</dd></div>
                </dl>
                @if($task->description)<div class="mt-4"><dt class="text-sm text-gray-500">Description</dt><dd class="mt-1">{{ $task->description }}</dd></div>@endif
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-4">Comments</h3>
                <form method="POST" action="{{ route('projects.tasks.comments.store', $task) }}" class="mb-4">
                    @csrf
                    <textarea name="content" rows="3" placeholder="Add a comment..." required class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    <button type="submit" class="mt-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Post Comment</button>
                </form>
                <div class="space-y-3">
                    @forelse($task->comments as $comment)
                        <div class="p-4 border rounded">
                            <div class="flex justify-between"><span class="font-medium">{{ $comment->user->name }}</span><span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span></div>
                            <p class="mt-2 text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">No comments yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
