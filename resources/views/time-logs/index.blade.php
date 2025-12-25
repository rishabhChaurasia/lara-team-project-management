<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Time Logs: {{ $task->title }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))<div class="px-4 py-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="px-4 py-3 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>@endif
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <div><h3 class="text-2xl font-bold">{{ $totalHours }}h {{ $remainingMinutes }}m</h3><p class="text-sm text-gray-600">Total Time Logged</p></div>
                    <div class="flex space-x-2">
                        <form method="POST" action="{{ route('projects.tasks.time-logs.start', $task) }}">@csrf<button class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Start Timer</button></form>
                        <form method="POST" action="{{ route('projects.tasks.time-logs.stop', $task) }}">@csrf<button class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Stop Timer</button></form>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-4">Recent Logs</h3>
                <div class="space-y-3">
                    @forelse($timeLogs as $log)
                        <div class="p-4 border rounded">
                            <div class="flex justify-between">
                                <div><span class="font-medium">{{ $log->user->name }}</span>
                                    @if($log->description)<p class="text-sm text-gray-600 mt-1">{{ $log->description }}</p>@endif
                                </div>
                                <div class="text-right"><div class="font-semibold">{{ floor($log->duration_minutes / 60) }}h {{ $log->duration_minutes % 60 }}m</div><div class="text-xs text-gray-500">{{ $log->started_at->format('M d, Y g:i A') }}</div></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No time logs yet. Start tracking time!</p>
                    @endforelse
                </div>
            </div>
            <div class="text-center">
                <a href="{{ route('projects.tasks.show', $task) }}" class="text-indigo-600 hover:text-indigo-800">← Back to Task</a>
            </div>
        </div>
    </div>
</x-app-layout>
