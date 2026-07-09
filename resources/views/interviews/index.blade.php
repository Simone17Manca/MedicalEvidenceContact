<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Colloqui
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ $role === 'business' ? 'Prepara inviti, slot e conferme per i candidati.' : 'Gestisci inviti ricevuti, scelta slot e stato dei colloqui.' }}
                </p>
            </div>

            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                Torna alla dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if ($role === 'business')
                <div class="grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
                    <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Candidature da pianificare</h3>
                                <p class="mt-1 text-sm text-gray-600">Seleziona annuncio e candidato per preparare un invito a colloquio.</p>
                            </div>
                            <span class="text-sm font-semibold text-gray-700">{{ $businessJobPostings->sum('applications_count') }} candidature</span>
                        </div>

                        <div class="mt-5 space-y-4">
                            @forelse ($businessJobPostings as $jobPosting)
                                <article class="rounded-md border border-gray-200 p-4">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $jobPosting->title }}</h4>
                                            <p class="mt-1 text-sm text-gray-600">{{ $jobPosting->workplace_address }}</p>
                                        </div>
                                        <span class="inline-flex w-fit rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                            {{ $jobPosting->applications_count }} candidature
                                        </span>
                                    </div>

                                    <div class="mt-4 grid gap-3">
                                        @forelse ($jobPosting->applications as $application)
                                            @php
                                                $professional = $application->professional;
                                            @endphp
                                            <div class="rounded-md bg-gray-50 p-3">
                                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900">
                                                            {{ $professional->first_name && $professional->last_name ? $professional->first_name.' '.$professional->last_name : $professional->name }}
                                                        </p>
                                                        <p class="mt-1 text-sm text-gray-600">{{ $professional->residence ?: 'Residenza non indicata' }}</p>
                                                    </div>
                                                    <button type="button" class="inline-flex w-fit items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700">
                                                        Seleziona
                                                    </button>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="rounded-md border border-dashed border-gray-300 p-3 text-sm text-gray-600">Nessuna candidatura per questo annuncio.</p>
                                        @endforelse
                                    </div>
                                </article>
                            @empty
                                <p class="rounded-md border border-dashed border-gray-300 p-5 text-sm text-gray-600">Non ci sono annunci business su cui pianificare colloqui.</p>
                            @endforelse
                        </div>
                    </section>

                    <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Nuovo invito a colloquio</h3>
                            <p class="mt-1 text-sm text-gray-600">Frontend pronto per collegare endpoint di creazione invito e slot multipli.</p>
                        </div>

                        <form method="POST" action="#" class="mt-5 space-y-5">
                            @csrf
                            <fieldset class="grid gap-4 md:grid-cols-2">
                                <label class="block">
                                    <span class="text-sm font-semibold text-gray-800">Annuncio</span>
                                    <select name="job_posting_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Seleziona annuncio</option>
                                        @foreach ($businessJobPostings as $jobPosting)
                                            <option value="{{ $jobPosting->id }}">{{ $jobPosting->title }}</option>
                                        @endforeach
                                    </select>
                                </label>

                                <label class="block">
                                    <span class="text-sm font-semibold text-gray-800">Candidatura</span>
                                    <select name="job_application_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Seleziona candidato</option>
                                        @foreach ($businessJobPostings as $jobPosting)
                                            @foreach ($jobPosting->applications as $application)
                                                @php
                                                    $professional = $application->professional;
                                                @endphp
                                                <option value="{{ $application->id }}">{{ $professional->first_name && $professional->last_name ? $professional->first_name.' '.$professional->last_name : $professional->name }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </label>

                                <label class="block md:col-span-2">
                                    <span class="text-sm font-semibold text-gray-800">Messaggio invito</span>
                                    <textarea name="message" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Aggiungi indicazioni utili per il colloquio"></textarea>
                                </label>
                            </fieldset>

                            <div class="rounded-md border border-gray-200 p-4">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900">Slot proposti</h4>
                                        <p class="mt-1 text-sm text-gray-600">La struttura dei campi e pronta per salvare piu slot nel backend.</p>
                                    </div>
                                    <button type="button" class="inline-flex w-fit items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700">
                                        Aggiungi slot
                                    </button>
                                </div>

                                <div class="mt-4 grid gap-3 lg:grid-cols-4">
                                    @foreach ([0, 1, 2] as $slotIndex)
                                        <label class="block">
                                            <span class="text-sm font-semibold text-gray-800">Data</span>
                                            <input type="date" name="slots[{{ $slotIndex }}][date]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </label>
                                        <label class="block">
                                            <span class="text-sm font-semibold text-gray-800">Ora inizio</span>
                                            <input type="time" name="slots[{{ $slotIndex }}][starts_at]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </label>
                                        <label class="block">
                                            <span class="text-sm font-semibold text-gray-800">Ora fine</span>
                                            <input type="time" name="slots[{{ $slotIndex }}][ends_at]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </label>
                                        <label class="block">
                                            <span class="text-sm font-semibold text-gray-800">Modalita</span>
                                            <select name="slots[{{ $slotIndex }}][mode]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="video">Videochiamata</option>
                                                <option value="onsite">In presenza</option>
                                                <option value="phone">Telefono</option>
                                            </select>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rounded-md bg-gray-50 p-4">
                                <h4 class="text-sm font-semibold text-gray-900">Stati previsti</h4>
                                <div class="mt-3 grid gap-2 text-sm sm:grid-cols-5">
                                    @foreach (['Invito inviato', 'Richiesto', 'Accettato', 'Rifiutato', 'Annullato'] as $status)
                                        <span class="rounded-full bg-white px-3 py-2 text-center font-semibold text-gray-700 ring-1 ring-gray-200">{{ $status }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex justify-end border-t border-gray-100 pt-5">
                                <button type="button" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white opacity-60" disabled>
                                    Invia invito
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            @else
                <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
                    <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Inviti e candidature</h3>
                            <p class="mt-1 text-sm text-gray-600">Qui appariranno gli inviti reali collegati alle candidature.</p>
                        </div>

                        <div class="mt-5 space-y-3">
                            @forelse ($professionalApplications as $application)
                                <article class="rounded-md border border-gray-200 p-4">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $application->jobPosting->title }}</h4>
                                            <p class="mt-1 text-sm text-gray-600">{{ $application->jobPosting->workplace_address }}</p>
                                        </div>
                                        <span class="inline-flex w-fit rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                            {{ str_replace('_', ' ', $application->status) }}
                                        </span>
                                    </div>
                                </article>
                            @empty
                                <p class="rounded-md border border-dashed border-gray-300 p-5 text-sm text-gray-600">Non hai ancora candidature con inviti a colloquio.</p>
                            @endforelse
                        </div>
                    </section>

                    <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Rispondi a un invito</h3>
                            <p class="mt-1 text-sm text-gray-600">Frontend pronto per collegare conferma slot, consenso e cambio stato.</p>
                        </div>

                        <form method="POST" action="#" class="mt-5 space-y-5">
                            @csrf

                            <label class="block">
                                <span class="text-sm font-semibold text-gray-800">Invito</span>
                                <select name="interview_invitation_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleziona invito</option>
                                    @foreach ($professionalApplications as $application)
                                        <option value="{{ $application->id }}">{{ $application->jobPosting->title }}</option>
                                    @endforeach
                                </select>
                            </label>

                            <div class="grid gap-3 md:grid-cols-3">
                                @foreach ([['martedi', '10:00 - 10:30', 'video'], ['mercoledi', '15:00 - 15:45', 'onsite'], ['venerdi', '09:00 - 09:30', 'phone']] as $slot)
                                    <label class="block rounded-md border border-gray-200 p-4">
                                        <input type="radio" name="selected_slot_id" value="{{ $slot[0] }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-sm font-semibold text-gray-900">{{ ucfirst($slot[0]) }}</span>
                                        <span class="mt-2 block text-sm text-gray-600">{{ $slot[1] }}</span>
                                        <span class="mt-2 inline-flex rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">{{ $slot[2] }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <label class="flex items-start gap-3 rounded-md bg-gray-50 p-4">
                                <input type="checkbox" name="contact_sharing_consent" value="1" class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span>
                                    <span class="block text-sm font-semibold text-gray-900">Consenso sblocco contatti</span>
                                    <span class="mt-1 block text-sm text-gray-600">Email e telefono saranno condivisi solo se il colloquio verra accettato.</span>
                                </span>
                            </label>

                            <label class="block">
                                <span class="text-sm font-semibold text-gray-800">Note per il business</span>
                                <textarea name="professional_note" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Eventuali indicazioni o richieste"></textarea>
                            </label>

                            <div class="rounded-md bg-gray-50 p-4">
                                <h4 class="text-sm font-semibold text-gray-900">Percorso risposta</h4>
                                <div class="mt-3 grid gap-2 text-sm sm:grid-cols-3">
                                    <span class="rounded-full bg-white px-3 py-2 text-center font-semibold text-gray-700 ring-1 ring-gray-200">Scegli slot</span>
                                    <span class="rounded-full bg-white px-3 py-2 text-center font-semibold text-gray-700 ring-1 ring-gray-200">Richiesto</span>
                                    <span class="rounded-full bg-white px-3 py-2 text-center font-semibold text-gray-700 ring-1 ring-gray-200">Accettato</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap justify-end gap-2 border-t border-gray-100 pt-5">
                                <button type="button" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 opacity-60" disabled>
                                    Rifiuta invito
                                </button>
                                <button type="button" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white opacity-60" disabled>
                                    Conferma slot
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
