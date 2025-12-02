<x-layouts.app.sidebar :title="'Genres'">
    <div class="mx-auto max-w-7xl p-6 text-gray-900 dark:text-gray-100 space-y-8">

        {{-- Page header --}}
        <header class="rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 px-6 py-5 text-white shadow-lg">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight">Genres</h1>
                    <p class="mt-1 text-sm text-indigo-100">
                        Manage your music genres and see how many songs belong to each.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <span class="inline-flex items-center gap-1 rounded-full bg-white/10 px-3 py-1 backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                        Genres active
                    </span>
                    <span class="inline-flex items-center gap-1 rounded-full bg-white/10 px-3 py-1 backdrop-blur">
                        <span class="font-semibold">{{ $genres->count() }}</span> total
                    </span>
                </div>
            </div>

            @if(session('success'))
                <div class="mt-4 rounded-lg bg-emerald-100/90 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-200 px-4 py-3 text-sm shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
        </header>

        {{-- Top: quick stats --}}
        <section class="grid gap-4 sm:grid-cols-3">
            {{-- Total genres --}}
            <article class="flex items-center gap-3 rounded-2xl border border-zinc-200/80 bg-white/95 p-4 shadow-sm dark:border-zinc-700/70 dark:bg-zinc-900/80">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-500/10 text-violet-500 dark:bg-violet-400/10 dark:text-violet-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M7 7h.01M4 4h16v16H4V4zm3 13.5h10M7 10h10M7 13h10" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Total Genres
                    </p>
                    <p class="text-2xl font-semibold">
                        {{ $genres->count() }}
                    </p>
                </div>
            </article>

            {{-- Genres with songs --}}
            <article class="flex items-center gap-3 rounded-2xl border border-zinc-200/80 bg-white/95 p-4 shadow-sm dark:border-zinc-700/70 dark:bg-zinc-900/80">
                @php
                    $withSongs = $genres->filter(fn($g) => ($g->songs_count ?? 0) > 0)->count();
                @endphp
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-500 dark:bg-emerald-400/10 dark:text-emerald-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 6h16M4 10h10M4 14h7M4 18h5" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Genres with Songs
                    </p>
                    <p class="text-2xl font-semibold">
                        {{ $withSongs }}
                    </p>
                </div>
            </article>

            {{-- Total songs across genres --}}
            <article class="flex items-center gap-3 rounded-2xl border border-zinc-200/80 bg-white/95 p-4 shadow-sm dark:border-zinc-700/70 dark:bg-zinc-900/80">
                @php
                    $totalSongsInGenres = $genres->sum('songs_count');
                @endphp
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-500 dark:bg-indigo-400/10 dark:text-indigo-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 19V6l12-2v13" />
                        <circle cx="6" cy="18" r="3" />
                        <circle cx="18" cy="16" r="3" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Songs in Genres
                    </p>
                    <p class="text-2xl font-semibold">
                        {{ $totalSongsInGenres }}
                    </p>
                </div>
            </article>
        </section>

        {{-- Middle: Genres table --}}
        <section class="rounded-2xl border border-zinc-200/80 bg-white/95 shadow-sm dark:border-zinc-700/70 dark:bg-zinc-900/80 overflow-hidden">
            <div class="border-b border-zinc-200/70 bg-zinc-50/80 px-4 py-3 text-sm dark:border-zinc-700/70 dark:bg-zinc-900/70">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Genres List</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            View, edit, or delete existing genres.
                        </p>
                    </div>

                    {{-- Simple search by name --}}
                    <form method="GET" action="{{ url()->current() }}" class="flex gap-2">
                        <div>
                            <label class="block text-[11px] font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">
                                Search
                            </label>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Genre name"
                                class="w-48 rounded-lg border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800/80 px-2.5 py-1.5 text-xs text-gray-900 dark:text-gray-100"
                            />
                        </div>
                        <div class="self-end pb-0.5">
                            <button
                                type="submit"
                                class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700"
                            >
                                Apply
                            </button>
                            @if(request()->filled('search'))
                                <a
                                    href="{{ url()->current() }}"
                                    class="ml-1 rounded-lg border border-zinc-300 bg-zinc-100 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-zinc-200 dark:border-zinc-700 dark:bg-zinc-800 dark:text-gray-200 dark:hover:bg-zinc-700"
                                >
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full table-auto text-sm">
                    <thead class="bg-zinc-100/80 text-left text-xs uppercase tracking-wide text-gray-600 dark:bg-zinc-900/80 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Description</th>
                            <th class="px-4 py-3">No. of Songs</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200/70 bg-white/80 dark:divide-zinc-800 dark:bg-transparent">
                        @forelse($genres as $genre)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/60">
                                <td class="px-4 py-3 align-middle font-medium">
                                    {{ $genre->name }}
                                </td>
                                <td class="px-4 py-3 align-middle text-xs text-gray-600 dark:text-gray-300">
                                    {{ $genre->description ?: '—' }}
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    <span class="inline-flex items-center rounded-full bg-zinc-100 px-2 py-0.5 text-xs text-gray-700 dark:bg-zinc-800 dark:text-gray-200">
                                        {{ $genre->songs_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 align-middle text-right">
                                    <div
                                        x-data="{ open:false, name:@js($genre->name), description:@js($genre->description) }"
                                        class="inline-flex items-center gap-2"
                                    >
                                        <button
                                            @click="open = true"
                                            class="text-xs font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-300 dark:hover:text-indigo-200 underline"
                                        >
                                            Edit
                                        </button>

                                        <form
                                            method="POST"
                                            action="{{ route('genres.destroy', $genre) }}"
                                            onsubmit="return confirm('Delete this genre?');"
                                            class="inline-block"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="text-xs font-medium text-rose-600 hover:text-rose-700 dark:text-rose-300 dark:hover:text-rose-200 underline"
                                            >
                                                Delete
                                            </button>
                                        </form>

                                        {{-- Edit modal --}}
                                        <div
                                            x-show="open"
                                            x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                                        >
                                            <div
                                                @click.away="open = false"
                                                class="w-full max-w-lg rounded-2xl border border-zinc-200/80 bg-white p-5 text-gray-900 shadow-xl dark:border-zinc-700/70 dark:bg-zinc-900 dark:text-gray-100"
                                            >
                                                <div class="mb-3 flex items-start justify-between gap-3">
                                                    <div>
                                                        <h3 class="text-lg font-semibold">Edit Genre</h3>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            Update the genre details below.
                                                        </p>
                                                    </div>
                                                    <button
                                                        type="button"
                                                        @click="open = false"
                                                        class="text-sm text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                                                    >
                                                        ✕
                                                    </button>
                                                </div>

                                                <form action="{{ route('genres.update', $genre) }}" method="POST" class="grid gap-3">
                                                    @csrf
                                                    @method('PUT')

                                                    <div>
                                                        <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                                                            Name *
                                                        </label>
                                                        <input
                                                            name="name"
                                                            x-model="name"
                                                            required
                                                            class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm"
                                                        />
                                                    </div>

                                                    <div>
                                                        <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                                                            Description
                                                        </label>
                                                        <textarea
                                                            name="description"
                                                            x-model="description"
                                                            rows="3"
                                                            class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm"
                                                        ></textarea>
                                                    </div>

                                                    <div class="mt-3 flex items-center gap-2">
                                                        <button
                                                            type="button"
                                                            @click="open = false"
                                                            class="rounded-lg border border-zinc-300 bg-zinc-100 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-zinc-200 dark:border-zinc-700 dark:bg-zinc-800 dark:text-gray-200 dark:hover:bg-zinc-700"
                                                        >
                                                            Cancel
                                                        </button>
                                                        <button
                                                            type="submit"
                                                            class="ml-auto rounded-lg bg-indigo-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700"
                                                        >
                                                            Save Changes
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No genres found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- If you later paginate $genres, you can uncomment this: --}}
            {{-- <div class="border-t border-zinc-200/70 bg-zinc-50/80 px-4 py-3 dark:border-zinc-800 dark:bg-zinc-900/80">
                {{ $genres->appends(request()->query())->links() }}
            </div> --}}
        </section>

        {{-- Bottom: Add Genre --}}
        <section class="rounded-2xl border border-zinc-200/80 bg-white/95 p-5 shadow-sm dark:border-zinc-700/70 dark:bg-zinc-900/80">
            <h2 class="mb-3 text-sm font-semibold tracking-wide uppercase text-gray-700 dark:text-gray-200">
                Add New Genre
            </h2>

            <form method="POST" action="{{ route('genres.store') }}" class="grid gap-4 md:grid-cols-3">
                @csrf

                <div class="md:col-span-1">
                    <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                        Genre Name *
                    </label>
                    <input
                        name="name"
                        required
                        placeholder="e.g., Pop"
                        class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm text-gray-900 dark:text-gray-100"
                    />
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                        Short Description (optional)
                    </label>
                    <input
                        name="description"
                        placeholder="Describe this genre briefly"
                        class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm text-gray-900 dark:text-gray-100"
                    />
                </div>

                <div class="md:col-span-3 flex justify-end">
                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900"
                    >
                        Add Genre
                    </button>
                </div>
            </form>
        </section>

    </div>
</x-layouts.app.sidebar>