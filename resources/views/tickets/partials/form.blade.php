<div class="grid grid-cols-1 gap-6">
    <div>
        <label for="title">Titre</label>
        <input id="title" class="block mt-1 w-full rounded border border-gray-300 shadow-sm" type="text" name="title" value="{{ old('title', $ticket->title ?? '') }}" required onfocus="this.select()" />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>
    <div>
        <label for="description">Description</label>
        <textarea id="description" class="block mt-1 w-full rounded border border-gray-300 shadow-sm" rows="4" name="description" required>{{ old('description', $ticket->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>
    <div>
        <label for="category_id">Categorie</label>
        <select id="category_id" class="block mt-1 w-full rounded border border-gray-300 shadow-sm" name="category_id" required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $ticket->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
    </div>
    <div>
        <label for="priority">Priorité</label>
        <select id="priority" class="block mt-1 w-full rounded border border-gray-300 shadow-sm" name="priority" required>
            @foreach (['low' => 'Faible', 'medium' => 'Moyenne', 'high' => 'Haute', 'critical' => 'Critique'] as $priority => $label)
                <option value="{{ $priority }}" {{ old('priority', $ticket->priority ?? '') == $priority ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('priority')" class="mt-2" />
    </div>
    @isset($ticket)
        <div>
            <label for="status">Statut</label>
            <select id="status" class="block mt-1 w-full rounded border border-gray-300 shadow-sm" name="status" required>
                @foreach (['open' => 'Ouvert', 'in_progress' => 'En cours', 'resolved' => 'Résolu', 'closed' => 'Fermer'] as $status => $label)
                    <option value="{{ $status }}" {{ old('status', $ticket->status ?? '') == $status ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
        @can('assign', $ticket)
        <div>
            <label for="assigned_to">Assigné à</label>
            <select id="assigned_to" class="block mt-1 w-full rounded border border-gray-300 shadow-sm" name="assigned_to" required>
                <option value="">Non assigné</option>
                @foreach (\App\Models\User::where('role', 'agent')->get() as $agent)
                    <option {{ old('assigned_to', $ticket->assigned_to ?? '') == $agent->id ? 'selected' : '' }} value="{{ $agent->id }}">{{ $agent->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
        </div>
        @endcan
    @endisset
</div>