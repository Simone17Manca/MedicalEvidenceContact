<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Candidature ricevute
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ $jobPosting->title }}
                </p>
            </div>

            <a href="{{ route('job-postings.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                Torna agli annunci
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <section class="mb-6 rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="grid gap-4 text-sm text-gray-700 sm:grid-cols-3">
                    <div>
                        <p class="font-semibold text-gray-900">Posizione</p>
                        <p class="mt-1">{{ $jobPosting->title }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Sede</p>
                        <p class="mt-1">{{ $jobPosting->workplace_address }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Candidature</p>
                        <p class="mt-1">{{ $applications->count() }}</p>
                    </div>
                </div>
            </section>

            @if ($applications->isEmpty())
                <section class="rounded-lg border border-dashed border-gray-300 bg-white p-10 text-center">
                    <h3 class="text-lg font-semibold text-gray-900">Nessuna candidatura ricevuta</h3>
                    <p class="mt-2 text-sm text-gray-600">Quando un professionista accettera questa posizione, comparira qui.</p>
                </section>
            @else
                <div class="grid gap-4">
                    @foreach ($applications as $application)
                        @php($professional = $application->professional)

                        <article class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $professional->first_name && $professional->last_name ? $professional->first_name.' '.$professional->last_name : $professional->name }}
                                    </h3>
                                    <dl class="mt-3 grid gap-3 text-sm text-gray-700 sm:grid-cols-2">
                                        <div>
                                            <dt class="font-semibold text-gray-900">Profilo</dt>
                                            <dd class="mt-1">Professionista</dd>
                                        </div>
                                        <div>
                                            <dt class="font-semibold text-gray-900">Residenza</dt>
                                            <dd class="mt-1">{{ $professional->residence ?: 'Non indicata' }}</dd>
                                        </div>
                                    </dl>
                                </div>

                                <div class="flex flex-col items-start gap-2 sm:items-end">
                                    <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                        {{ str_replace('_', ' ', $application->status) }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        {{ $application->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
