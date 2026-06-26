<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Crea annuncio</h2>
            <p class="mt-1 text-sm text-gray-600">Compila i dettagli richiesti dal PRD per pubblicare una nuova opportunita.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <x-validation-errors class="mb-6" />

                <form method="POST" action="{{ route('job-postings.store') }}" class="space-y-6">
                    @csrf

                    @include('job-postings._form')

                    <div class="flex items-center justify-between border-t border-gray-100 pt-6">
                        <a href="{{ route('job-postings.index') }}" class="text-sm text-gray-600 underline hover:text-gray-900">
                            Annulla
                        </a>

                        <x-button>
                            Pubblica annuncio
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
