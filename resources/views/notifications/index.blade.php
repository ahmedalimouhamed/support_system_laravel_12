<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 border-gray-200 border-b">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium">Historique des Notifications</h3>
                    </div>
                </div>

                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-blue-700 text-sm">Marquer toutes comme lues</button>
                    </form>
                @endif
            </div>
            <div class="space-y-4">
                @forelse($notifications as $notification)
                    @include('notifications.partials.notification', ['notification' => $notification])
                @empty
                    <p class="text-center text-gray-500">Aucune notification</p>
                @endforelse
            </div>
            <div class="p-6 bg-white border-b border-gray-200">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>

</x-app-layout>