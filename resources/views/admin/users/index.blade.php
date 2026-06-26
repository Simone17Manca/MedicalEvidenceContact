<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Utenti</h2>
                <p class="mt-1 text-sm text-gray-600">Gestione completa degli account.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-500">Crea utente</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @session('status')
                <div class="mb-6 rounded-md bg-green-50 p-4 text-sm font-medium text-green-700">{{ $value }}</div>
            @endsession

            <div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left font-semibold text-gray-900">
                        <tr>
                            <th class="px-4 py-3">Nome</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Ruolo</th>
                            <th class="px-4 py-3">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $user->role }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">Modifica</a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Eliminare questo utente?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800">Elimina</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $users->links() }}</div>
        </div>
    </div>
</x-app-layout>
