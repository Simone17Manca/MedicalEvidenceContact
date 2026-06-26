<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ $jobPosting->title }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ $jobPosting->contract_type }} - {{ $jobPosting->workplace_address }}</p>
            </div>

            <a href="{{ route('job-postings.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                Torna agli annunci
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            @session('status')
                <div class="mb-6 rounded-md bg-green-50 p-4 text-sm font-medium text-green-700">
                    {{ $value }}
                </div>
            @endsession

            <article class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <span class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                            {{ $jobPosting->status === 'active' ? 'Attivo' : 'Scaduto' }}
                        </span>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">Dettagli posizione</h3>
                    </div>

                    @if ($role === 'business')
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('job-postings.edit', $jobPosting) }}" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-500">
                                Modifica
                            </a>
                            <a href="{{ route('job-postings.applications', $jobPosting) }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                                Candidature
                            </a>
                            <form method="POST" action="{{ route('job-postings.destroy', $jobPosting) }}" onsubmit="return confirm('Eliminare questo annuncio?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-500">
                                    Elimina
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <div class="mt-6 grid gap-4 text-sm text-gray-700 sm:grid-cols-3">
                    <div class="rounded-md bg-gray-50 p-4">
                        <p class="font-semibold text-gray-900">Posizioni</p>
                        <p class="mt-1">{{ $jobPosting->positions }}</p>
                    </div>
                    <div class="rounded-md bg-gray-50 p-4">
                        <p class="font-semibold text-gray-900">Contratto</p>
                        <p class="mt-1">{{ $jobPosting->contract_type }}</p>
                    </div>
                    <div class="rounded-md bg-gray-50 p-4">
                        <p class="font-semibold text-gray-900">Scadenza</p>
                        <p class="mt-1">{{ $jobPosting->expires_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                <div class="mt-6 space-y-5 border-t border-gray-100 pt-6 text-sm leading-6 text-gray-700">
                    <div>
                        <p class="font-semibold text-gray-900">Descrizione</p>
                        <p class="mt-2 whitespace-pre-line">{{ $jobPosting->description }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-900">Sede</p>
                        <p class="mt-2">{{ $jobPosting->workplace_address }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-900">Abilita richieste</p>
                        <p class="mt-2 whitespace-pre-line">{{ $jobPosting->required_skills ?: 'Non specificate' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-900">Retribuzione</p>
                        <p class="mt-2">
                            @if ($jobPosting->salary_min || $jobPosting->salary_max)
                                {{ $jobPosting->salary_min ? 'EUR '.number_format((float) $jobPosting->salary_min, 0, ',', '.') : 'Da definire' }}
                                -
                                {{ $jobPosting->salary_max ? 'EUR '.number_format((float) $jobPosting->salary_max, 0, ',', '.') : 'Da definire' }}
                            @else
                                Da definire
                            @endif
                        </p>
                    </div>
                </div>

                @if ($role === 'professional')
                    <div class="mt-6 flex justify-end border-t border-gray-100 pt-6">
                        @if ($jobPosting->applications->isNotEmpty())
                            <span class="inline-flex items-center rounded-md bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700">
                                Candidatura {{ str_replace('_', ' ', $jobPosting->applications->first()->status) }}
                            </span>
                        @else
                            <form method="POST" action="{{ route('job-applications.store', $jobPosting) }}">
                                @csrf
                                <x-button>
                                    Candidati
                                </x-button>
                            </form>
                        @endif
                    </div>
                @endif
            </article>
        </div>
    </div>
</x-app-layout>
