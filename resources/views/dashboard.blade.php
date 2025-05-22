<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Mes Tickets</h3>

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Tickets ouverts</p>
                            <p class="text-lg font-semibold">{{ auth()->user()->tickets()->where('status', 'ouvert')->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tickets en cours</p>
                            <p class="text-lg font-semibold">{{ auth()->user()->tickets()->where('status', 'en cours')->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tickets Résolus</p>
                            <p class="text-lg font-semibold">{{ auth()->user()->tickets()->where('status', 'resolu')->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tickets en attente</p>
                            <p class="text-lg font-semibold">{{ auth()->user()->tickets()->where('status', 'en attente')->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tickets total</p>
                            <p class="text-lg font-semibold">{{ auth()->user()->tickets()->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg md:col-span-2">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium mb-4">Mes Tickets Récents</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Créé le</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigné à</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse (auth()->user()->tickets()->latest()->take(5)->get() as $ticket)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ticket->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><a href="{{ route('tickets.show', $ticket->id) }}">{{ $ticket->title }}</a></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><x-status-badge :status="$ticket->status" /></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($ticket->priority) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \App\Models\User::find($ticket->assigned_to)->name ?? 'Non assigné' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <a href="{{ route('tickets.show', $ticket->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Voir</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Aucun ticket</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!--Notifications Récentes-->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg md:col-span-2">
                <div class="p-6 text-gray-900 dark:text-gray-100 border-gray-200 border-b">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Mes Notifications Récentes
                    </h2>
                    <div class="space-y-4">
                        @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                            @include('notifications.partials.notification', ['notification' => $notification])
                        @empty
                            <p class="text-center text-gray-500">Aucune notification</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
