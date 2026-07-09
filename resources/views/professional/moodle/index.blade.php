<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">I miei Moodle</h2>
            <p class="text-sm text-gray-600">Collega i tuoi account Moodle e prepara la sincronizzazione degli attestati.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            @session('status')
                <div class="mb-6 rounded-md bg-green-50 p-4 text-sm font-medium text-green-700">{{ $value }}</div>
            @endsession

            <x-validation-errors class="mb-6" />

            <section class="mb-8 bg-white p-6 shadow-sm ring-1 ring-gray-200 sm:rounded-lg">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-semibold text-gray-900">Collega account Moodle</h3>
                    <p class="text-sm text-gray-600">Se i dati corrispondono a un account Moodle, riceverai un codice all email associata.</p>
                </div>

                @if ($moodleSites->isEmpty())
                    <div class="mt-5 rounded-md border border-dashed border-gray-300 p-5 text-sm text-gray-600">
                        Nessun sito Moodle disponibile.
                    </div>
                @else
                    <form method="POST" action="{{ route('professional.moodle.start') }}" class="mt-5 grid gap-4 lg:grid-cols-3">
                        @csrf

                        <div>
                            <x-label for="moodle_site_id" value="Sito Moodle" />
                            <select id="moodle_site_id" name="moodle_site_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @foreach ($moodleSites as $moodleSite)
                                    <option value="{{ $moodleSite->id }}" @selected(old('moodle_site_id') == $moodleSite->id)>{{ $moodleSite->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-label for="lookup_type" value="Tipo dato" />
                            <select id="lookup_type" name="lookup_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="email" @selected(old('lookup_type') === 'email')>Email Moodle</option>
                                <option value="username" @selected(old('lookup_type') === 'username')>Username Moodle</option>
                            </select>
                        </div>

                        <div>
                            <x-label for="lookup_value" value="Email o username Moodle" />
                            <x-input id="lookup_value" name="lookup_value" type="text" class="mt-1 block w-full" value="{{ old('lookup_value') }}" required />
                        </div>

                        <div class="flex justify-end border-t border-gray-100 pt-5 lg:col-span-3">
                            <x-button>Collega account</x-button>
                        </div>
                    </form>
                @endif
            </section>

            <section class="bg-white p-6 shadow-sm ring-1 ring-gray-200 sm:rounded-lg">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-semibold text-gray-900">Account collegati</h3>
                    <p class="text-sm text-gray-600">I collegamenti attivi saranno usati per la sincronizzazione attestati nella fase successiva.</p>
                </div>

                @if ($moodleUserLinks->isEmpty())
                    <div class="mt-5 rounded-md border border-dashed border-gray-300 p-5 text-sm text-gray-600">
                        Nessun account Moodle collegato.
                    </div>
                @else
                    <div class="mt-5 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                                <tr>
                                    <th class="px-3 py-2">Sito</th>
                                    <th class="px-3 py-2">Username</th>
                                    <th class="px-3 py-2">ID Moodle</th>
                                    <th class="px-3 py-2">Stato</th>
                                    <th class="px-3 py-2">Collegato il</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($moodleUserLinks as $link)
                                    <tr>
                                        <td class="px-3 py-2 text-gray-700">{{ $link->moodleSite->name }}</td>
                                        <td class="px-3 py-2 text-gray-700">{{ $link->moodle_username ?: '-' }}</td>
                                        <td class="px-3 py-2 text-gray-700">{{ $link->moodle_user_id }}</td>
                                        <td class="px-3 py-2 text-gray-700">{{ $link->status }}</td>
                                        <td class="px-3 py-2 text-gray-700">{{ $link->linked_at?->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>