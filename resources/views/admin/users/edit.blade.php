<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Modifica utente</h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            @session('status')
                <div class="mb-6 rounded-md bg-green-50 p-4 text-sm font-medium text-green-700">{{ $value }}</div>
            @endsession

            <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <x-validation-errors class="mb-6" />

                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    @include('admin.users._form')

                    <div class="flex items-center justify-between border-t border-gray-100 pt-6">
                        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 underline hover:text-gray-900">Torna agli utenti</a>
                        <x-button>Salva modifiche</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
