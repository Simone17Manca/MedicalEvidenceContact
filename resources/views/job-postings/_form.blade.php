@php
    $contractTypes = ['Tempo indeterminato', 'Tempo determinato', 'Part-time', 'Collaborazione', 'Libero professionista', 'Somministrazione'];
    $selectedContractType = old('contract_type', $jobPosting->contract_type ?? '');
@endphp

<div>
    <x-label for="title" value="Titolo" />
    <x-input id="title" class="mt-1 block w-full" name="title" :value="old('title', $jobPosting->title ?? '')" required />
</div>

<div>
    <x-label for="description" value="Descrizione" />
    <textarea id="description" name="description" rows="6" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $jobPosting->description ?? '') }}</textarea>
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <x-label for="positions" value="Numero posizioni" />
        <x-input id="positions" class="mt-1 block w-full" type="number" min="1" name="positions" :value="old('positions', $jobPosting->positions ?? 1)" required />
    </div>

    <div>
        <x-label for="contract_type" value="Tipo contratto" />
        <select id="contract_type" name="contract_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Seleziona</option>
            @foreach ($contractTypes as $contractType)
                <option value="{{ $contractType }}" @selected($selectedContractType === $contractType)>{{ $contractType }}</option>
            @endforeach
        </select>
    </div>
</div>

<div>
    <x-label for="workplace_address" value="Indirizzo sede lavoro" />
    <x-input id="workplace_address" class="mt-1 block w-full" name="workplace_address" :value="old('workplace_address', $jobPosting->workplace_address ?? '')" required />
</div>

<div>
    <x-label for="required_skills" value="Abilita richieste (opzionale)" />
    <textarea id="required_skills" name="required_skills" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('required_skills', $jobPosting->required_skills ?? '') }}</textarea>
</div>

<div class="grid gap-4 sm:grid-cols-3">
    <div>
        <x-label for="salary_min" value="Retribuzione minima" />
        <x-input id="salary_min" class="mt-1 block w-full" type="number" min="0" step="0.01" name="salary_min" :value="old('salary_min', $jobPosting->salary_min ?? '')" />
    </div>

    <div>
        <x-label for="salary_max" value="Retribuzione massima" />
        <x-input id="salary_max" class="mt-1 block w-full" type="number" min="0" step="0.01" name="salary_max" :value="old('salary_max', $jobPosting->salary_max ?? '')" />
    </div>

    <div>
        <x-label for="expires_at" value="Data scadenza" />
        <x-input id="expires_at" class="mt-1 block w-full" type="date" name="expires_at" :value="old('expires_at', isset($jobPosting) && $jobPosting->expires_at ? $jobPosting->expires_at->format('Y-m-d') : '')" required />
    </div>
</div>

@isset($jobPosting)
    <div>
        <x-label for="status" value="Stato annuncio" />
        <select id="status" name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="active" @selected(old('status', $jobPosting->status) === 'active')>Attivo</option>
            <option value="expired" @selected(old('status', $jobPosting->status) === 'expired')>Scaduto</option>
        </select>
    </div>
@endisset
