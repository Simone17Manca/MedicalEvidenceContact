<x-guest-layout>
    <div class="min-h-screen bg-gray-950 px-4 py-8 text-white sm:py-12">
        <div class="mx-auto grid min-h-[calc(100vh-4rem)] w-full max-w-5xl items-center gap-8 lg:grid-cols-[0.9fr_1.1fr]">
            <section class="hidden lg:block">
                <div class="mb-8">
                    <x-authentication-card-logo />
                </div>

                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-300">Area riservata</p>
                <h1 class="mt-4 text-4xl font-semibold leading-tight">Registra un admin</h1>
                <p class="mt-5 max-w-md text-sm leading-6 text-gray-300">
                    Questo ingresso crea account staff con ruolo amministratore. Non e parte della registrazione pubblica per professionisti e aziende.
                </p>

                <div class="mt-8 rounded-md border border-white/10 bg-white/5 p-4 text-sm text-gray-300">
                    <p class="font-semibold text-white">Account admin</p>
                    <p class="mt-1">Gli admin possono accedere solo dalla pagina dedicata di amministrazione.</p>
                </div>
            </section>

            <section class="mx-auto w-full max-w-md rounded-lg bg-white p-6 text-gray-900 shadow-xl">
                <div class="mb-6 lg:hidden">
                    <x-authentication-card-logo />
                </div>

                <div class="mb-6">
                    <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Admin</p>
                    <h2 class="mt-2 text-2xl font-semibold text-gray-900">Nuovo account staff</h2>
                    <p class="mt-2 text-sm text-gray-600">Crea un utente amministratore per la gestione interna.</p>
                </div>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('admin.register.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <x-label for="first_name" value="Nome" />
                            <x-input id="first_name" class="mt-1 block w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="given-name" />
                        </div>

                        <div>
                            <x-label for="last_name" value="Cognome" />
                            <x-input id="last_name" class="mt-1 block w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" />
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-label for="email" value="Email admin" />
                        <x-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    </div>

                    <div class="mt-4">
                        <x-label for="phone" value="Telefono staff" />
                        <x-input id="phone" class="mt-1 block w-full" type="text" name="phone" :value="old('phone')" required autocomplete="tel" />
                    </div>

                    <div class="mt-4">
                        <x-label for="password" value="Password" />
                        <x-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
                    </div>

                    <div class="mt-4">
                        <x-label for="password_confirmation" value="Conferma password" />
                        <x-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('admin.login') }}">
                            Login admin
                        </a>

                        <x-button>
                            Crea admin
                        </x-button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-guest-layout>
