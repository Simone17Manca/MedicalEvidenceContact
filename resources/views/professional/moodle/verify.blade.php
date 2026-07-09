<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Conferma codice Moodle</h2>
            <p class="text-sm text-gray-600">{{ $attempt->moodleSite->name }}</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-xl px-4 sm:px-6 lg:px-8">
            @session('status')
                <div class="mb-6 rounded-md bg-green-50 p-4 text-sm font-medium text-green-700">{{ $value }}</div>
            @endsession

            <x-validation-errors class="mb-6" />

            <section class="bg-white p-6 shadow-sm ring-1 ring-gray-200 sm:rounded-lg">
                <form id="cancel-moodle-attempt" method="POST" action="{{ route('professional.moodle.cancel', $attempt) }}">
                    @csrf
                </form>

                <form method="POST" action="{{ route('professional.moodle.verify', $attempt) }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-label for="code" value="Codice ricevuto" />
                        <x-input id="code" name="code" type="text" inputmode="numeric" maxlength="6" class="mt-1 block w-full" required />
                        <x-input-error for="code" class="mt-2" />
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-100 pt-5">
                        <button type="submit" form="cancel-moodle-attempt" class="text-sm font-semibold text-gray-600 hover:text-gray-900">Annulla</button>
                        <x-button>Conferma</x-button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>
