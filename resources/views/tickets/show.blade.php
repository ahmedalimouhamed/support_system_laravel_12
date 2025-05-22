<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Ticket #{{ $ticket->id }} - {{ $ticket->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 border-gray-200 border-b flex justify-between">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $ticket->title }}
                    </h2>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <a href="{{ route('tickets.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Retour</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full divide-gray-200">
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">ID</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ticket->id }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Crée le</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Categorie</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ticket->category->name }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Statut</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><x-status-badge :status="$ticket->status" /></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Priorité</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($ticket->priority) }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Créé par</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ticket->user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Assigné à</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \App\Models\User::find($ticket->assigned_to)->name ?? 'Non assigné' }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="flex justify-between items-center mt-4">
                            @can('update', $ticket)
                            <div>
                                <a href="{{ route('tickets.edit', $ticket->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Modifier</a>
                            </div>
                            @endcan
                            @can('delete', $ticket)
                            <div>
                                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Voulez-vous vraiment supprimer ce ticket ?')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Supprimer</button>
                                </form>
                            </div>
                            @endcan
                            <div>
                                <a href="{{ route('tickets.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Retour</a>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg mb-6 mt-6">
                            <p class="whitespace-pre-line">{{ $ticket->description }}</p>
                        </div>

                        <div class="mb-6">
                            <h4 class="font-medium text-lg mb-4">Réponses</h4>
                            @foreach ($ticket->responses as $response)
                                <div class="mb-4 p-4 border rounded-lg {{ $response->user_id == auth()->id() ? 'bg-blue-50 border-blue-200' : 'bg-white' }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="font-medium text-sm text-gray-600">
                                            {{ $response->user->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $response->created_at->format('d/m/Y H:i') }}
                                            @if($response->edited_at)
                                                <span class="text-xs text-gray-500 ml-2">(Modifié {{ $response->edited_at->format('d/m/Y H:i') }})</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="whitespace-pre-line">{{ $response->content }}</p>
                                    @can('update', $response)
                                        <div class="mt-2">
                                            <a href="#" onclick="event.preventDefault(); document.getElementById('edit-response-{{ $response->id }}').classList.toggle('hidden');" class="text-sm text-blue-600 hover:text-blue-800">Modifier</a>
                                        </div>
                                        <div id="edit-response-{{ $response->id }}" class="hidden mt-2">
                                            <form action="{{ route('tickets.responses.update', $response->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <textarea name="content" id="content" class="w-full h-24 p-2 border border-gray-300 rounded" rows="3" required>{{ $response->content }}</textarea>
                                                <div class="mt-2">
                                                    <input type="text" name="edit_reason" id="edit_reason" class="w-full p-2 border border-gray-300 rounded" placeholder="Motif de modification">
                                                </div>
                                                <div class="mt-2 flex flex-end space-x-2">
                                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Enregistrer</button>
                                                    <button type="button" onclick="document.getElementById('edit-response-{{ $response->id }}').classList.add('hidden');" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Annuler</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endcan
                                </div>
                            @endforeach
                            @can('respond', $ticket)
                                <div class="mt-4">
                                    <h4 class="font-medium text-lg mb-4">Ajouter une réponse</h4>
                                    <form action="{{ route('tickets.responses.store', $ticket->id) }}" method="POST">
                                        @csrf
                                        <textarea name="content" id="content" class="w-full h-24 p-2 border border-gray-300 rounded" rows="3" required placeholder="Contenu de la réponse"></textarea>
                                        <div class="mt-2 flex flex-end space-x-2">
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Envoyer</button>
                                        </div>
                                    </form>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>