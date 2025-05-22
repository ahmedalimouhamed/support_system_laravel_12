<div class="p-4 border rounded-lg {{ $notification->unread() ? 'bg-blue-50 border-blue-200' : 'bg-white' }}">
    <div class="flex justify-between items-start">
        <div>
            {{-- <h4 class="font-medu">{{ $notification->data['title'] }}</h4> --}}
            <p class="text-sm text-gray-600">{{ $notification->data['message'] }}</p>
        </div>
        <div class="text-sm text-gray-500">
            {{ $notification->created_at->diffForHumans() }}
            @if($notification->unread())
                <span class="ml-2 inline-block w-2 h-2 rounded-full bg-blue-500"></span>
            @endif
        </div>
    </div>
    @if(isset($notification->data['link']))
        <div class="mt-2 text-right">
            <a href="{{ route('notifications.mark-as-read', $notification->id) }}" class="text-blue-600 hover:text-blue-800">Voir les détails</a>
        </div>
    @endif
</div>