<div x-data="{ open: false }" class="relative ml-3">
    <button @click="open = ! open" class="p-1 test-gray-400 hover:text-gray-500 relative">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V9c0-1.105-.895-2-2-2h-5V4a2 2 0 00-2-2h-5c-1.105 0-2 .895-2 2v5c0 1.105.895 2 2 2h.586l1.414 1.414c.171.171.452.216.73.182V17m7-10h-2V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v3m12 0h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>

        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-2 text-xs font-medium rounded-full bg-red-600 text-white">{{ auth()->user()->unreadNotifications->count() }}</span>
        @endif
    </button>

    <div x-show="open" 
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
    >
        <div class="py-1">
            <div class="px-4 py-2 border-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium">Notifications</h3>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-blue-700 text-sm">Marquer toutes comme lues</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="max-h-69 overflow-y-auto">
                @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                    <a href={{ route('notifications.mark-as-read', $notification->id) }} class="block px-4 py-3 border-b hover-gray-50 {{ $notification->unread() ? 'bg-blue-50' : '' }}">
                        <div class="flex justify-between">
                            <div>
                                {{-- <p class="font-medium">{{ $notification->data['title'] }}</p> --}}
                                <p class="text-sm text-gray-600">{{ $notification->data['message'] }}</p>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $notification->created_at->diffForHumans() }}
                                @if($notification->unread())
                                    <span class="ml-2 inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-center text-gray-500">Aucune notification</p>
                @endforelse
            </div>
            <div class="px-4 py-2 border-t text-center">
                <a href="{{ route('notifications.index') }}" class="text-blue-700 text-sm">Voir toutes les notifications</a>
            </div>
        </div>
    </div>
</div>