<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard admin</h2>
                <p class="mt-1 text-sm text-gray-600">Vista operativa su utenti e annunci della piattaforma.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-2">
                <a href="{{ route('admin.users.index') }}" class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200 transition hover:ring-indigo-300">
                    <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Utenti</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $usersCount }}</p>
                    <p class="mt-1 text-sm text-gray-600">Visualizza e gestisci tutti gli account.</p>
                </a>

                <a href="{{ route('admin.job-postings.index') }}" class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200 transition hover:ring-indigo-300">
                    <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Annunci</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $jobPostingsCount }}</p>
                    <p class="mt-1 text-sm text-gray-600">Visualizza e gestisci tutti gli annunci.</p>
                </a>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Ultimi utenti</h3>
                    <div class="mt-4 divide-y divide-gray-100">
                        @foreach ($recentUsers as $user)
                            <a href="{{ route('admin.users.edit', $user) }}" class="block py-3 text-sm hover:text-indigo-700">
                                <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                                <span class="text-gray-500">- {{ $user->role }} - {{ $user->email }}</span>
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Ultimi annunci</h3>
                    <div class="mt-4 divide-y divide-gray-100">
                        @foreach ($recentJobPostings as $jobPosting)
                            <a href="{{ route('admin.job-postings.edit', $jobPosting) }}" class="block py-3 text-sm hover:text-indigo-700">
                                <span class="font-semibold text-gray-900">{{ $jobPosting->title }}</span>
                                <span class="text-gray-500">- {{ $jobPosting->owner?->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
