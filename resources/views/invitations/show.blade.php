<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Project Invitation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">You've been invited to join a project!</h3>
                    
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p><strong>Project:</strong> {{ $invitation->project->name }}</p>
                        <p><strong>From:</strong> {{ $invitation->inviter->name }}</p>
                        <p><strong>Role:</strong> {{ ucfirst($invitation->role) }}</p>
                        <p><strong>Invited Email:</strong> {{ $invitation->email }}</p>
                    </div>
                    
                    <p class="mb-6">You have been invited to join the project "{{ $invitation->project->name }}" as a {{ $invitation->role }}.</p>
                    
                    @auth
                        @if(auth()->user()->email === $invitation->email)
                            <form method="POST" action="{{ route('invitations.accept', $invitation->token) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Accept Invitation
                                </button>
                            </form>
                        @else
                            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                                <p>This invitation was sent to {{ $invitation->email }}, but you are currently logged in as {{ auth()->user()->email }}.</p>
                                <p>Please log out and log in with the correct account, or contact the project owner.</p>
                            </div>
                        @endif
                    @else
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4" role="alert">
                            <p>You need to log in to accept this invitation. The invitation was sent to {{ $invitation->email }}.</p>
                        </div>
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Log In
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>