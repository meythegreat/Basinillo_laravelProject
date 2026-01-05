<x-layouts.app.sidebar :title="'Music Dashboard'">
    <div class="w-full p-6 xl:p-8 2xl:p-10 text-gray-900 dark:text-gray-100 space-y-8">

        {{-- Page header (separate look is OK) --}}
        <header class="rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 px-6 py-5 text-white shadow-lg">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight">Music Dashboard</h1>
                    <p class="mt-1 text-sm text-indigo-100">
                        Overview of your songs, genres, and listening time.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <span class="inline-flex items-center gap-1 rounded-full bg-white/10 px-3 py-1 backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                        Library active
                    </span>
                    <span class="inline-flex items-center gap-1 rounded-full bg-white/10 px-3 py-1 backdrop-blur">
                        <span class="font-semibold">{{ $totalSongs ?? 0 }}</span> songs
                    </span>
                    <span class="inline-flex items-center gap-1 rounded-full bg-white/10 px-3 py-1 backdrop-blur">
                        <span class="font-semibold">{{ $totalGenres ?? 0 }}</span> genres
                    </span>
                </div>
            </div>

            @if(session('success'))
                <div class="mt-4 rounded-lg bg-emerald-100/90 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-200 px-4 py-3 text-sm shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
        </header>

        {{-- Top: statistic cards (same card style) --}}
        <section class="grid gap-4 sm:grid-cols-3">
            {{-- Total Songs --}}
            <article class="flex items-center gap-3 rounded-2xl border border-zinc-200/80 bg-white/95 p-4 shadow-sm dark:border-zinc-700/70 dark:bg-zinc-900/80">
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
                        Total Songs
                    </p>
                    <p class="text-2xl font-semibold">
                        {{ $totalSongs ?? 0 }}
                    </p>
                </div>
            </article>

            {{-- Total Genres --}}
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
                        {{ $totalGenres ?? 0 }}
                    </p>
                </div>
            </article>

            {{-- Total Duration --}}
            <article class="flex items-center gap-3 rounded-2xl border border-zinc-200/80 bg-white/95 p-4 shadow-sm dark:border-zinc-700/70 dark:bg-zinc-900/80">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-500/10 text-rose-500 dark:bg-rose-400/10 dark:text-rose-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 6v6l4 2m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Total Duration
                    </p>
                    <p class="text-2xl font-semibold">
                        {{ intdiv($totalDuration ?? 0, 60) }}m {{ ($totalDuration ?? 0) % 60 }}s
                    </p>
                </div>
            </article>
        </section>

        {{-- Middle: Songs Library (same card style, with search + filter) --}}
        <section class="rounded-2xl border border-zinc-200/80 bg-white/95 shadow-sm dark:border-zinc-700/70 dark:bg-zinc-900/80 overflow-hidden">
            <div class="border-b border-zinc-200/70 bg-zinc-50/80 px-4 py-3 text-sm dark:border-zinc-700/70 dark:bg-zinc-900/70">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Songs Library</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Manage and search your songs; filter by genre.
                        </p>
                    </div>

                    {{-- Search + Filter --}}
                    <form method="GET" action="{{ url()->current() }}" class="flex flex-col gap-2 sm:flex-row sm:items-end">
                        <div class="sm:w-52">
                            <label class="block text-[11px] font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">
                                Search
                            </label>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Title or artist"
                                class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800/80 px-2.5 py-1.5 text-xs text-gray-900 dark:text-gray-100"
                            />
                        </div>

                        <div class="sm:w-48">
                            <label class="block text-[11px] font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">
                                Genre
                            </label>
                            <select
                                name="genre"
                                class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800/80 px-2.5 py-1.5 text-xs text-gray-900 dark:text-gray-100"
                            >
                                <option value="">All genres</option>
                                @foreach($genres as $g)
                                    <option value="{{ $g->id }}" @selected(request('genre') == $g->id)>
                                        {{ $g->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button
                                type="submit"
                                class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700"
                            >
                                Apply
                            </button>
                            @if(request()->has('search') || request()->has('genre'))
                                <a
                                    href="{{ url()->current() }}"
                                    class="rounded-lg border border-zinc-300 bg-zinc-100 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-zinc-200 dark:border-zinc-700 dark:bg-zinc-800 dark:text-gray-200 dark:hover:bg-zinc-700"
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
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Artist</th>
                            <th class="px-4 py-3">Genre</th>
                            <th class="px-4 py-3">Duration</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200/70 bg-white/80 dark:divide-zinc-800 dark:bg-transparent">
                        @forelse($songs as $song)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/60">
                                <td class="px-4 py-3 align-middle">
                                    <div class="font-medium">{{ $song->title ?? '—' }}</div>
                                    @if($song->release_year)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $song->release_year }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    {{ $song->artist ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    <span class="inline-flex items-center rounded-full bg-zinc-100 px-2 py-0.5 text-xs text-gray-700 dark:bg-zinc-800 dark:text-gray-200">
                                        {{ $song->genre->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    @if($song->duration_seconds)
                                        {{ intdiv($song->duration_seconds, 60) }}m {{ $song->duration_seconds % 60 }}s
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 align-middle text-right">
                                    <div
                                        x-data="{
                                            open:false,
                                            minutes: {{ (int) ($song->duration_seconds ? intdiv($song->duration_seconds,60) : 0) }},
                                            seconds: {{ (int) ($song->duration_seconds ? $song->duration_seconds % 60 : 0) }},
                                            title:@js($song->title),
                                            artist:@js($song->artist),
                                            genre:{{ $song->genre_id ?? 'null' }},
                                            year:{{ $song->release_year ?? 'null' }},
                                            notes:@js($song->notes)
                                        }"
                                        class="inline-flex items-center gap-2"
                                    >
                                        <button
                                            @click="open=true"
                                            class="text-xs font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-300 dark:hover:text-indigo-200 underline"
                                        >
                                            Edit
                                        </button>

                                        <form
                                            method="POST"
                                            action="{{ route('songs.destroy', $song) }}"
                                            onsubmit="return confirm('Delete this song?');"
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
                                                @click.away="open=false"
                                                class="w-full max-w-2xl rounded-2xl border border-zinc-200/80 bg-white p-5 text-gray-900 shadow-xl dark:border-zinc-700/70 dark:bg-zinc-900 dark:text-gray-100"
                                            >
                                                <div class="mb-3 flex items-start justify-between gap-3">
                                                    <div>
                                                        <h3 class="text-lg font-semibold">Edit Song</h3>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            Update details and save changes.
                                                        </p>
                                                    </div>
                                                    <button
                                                        type="button"
                                                        @click="open=false"
                                                        class="text-sm text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                                                    >
                                                        ✕
                                                    </button>
                                                </div>

                                                <form action="{{ route('songs.update', $song) }}" method="POST" class="grid gap-3">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="grid gap-3 md:grid-cols-2">
                                                        <div class="md:col-span-2">
                                                            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                                                                Title *
                                                            </label>
                                                            <input
                                                                name="title"
                                                                x-model="title"
                                                                required
                                                                class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm"
                                                            />
                                                        </div>

                                                        <div>
                                                            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                                                                Artist
                                                            </label>
                                                            <input
                                                                name="artist"
                                                                x-model="artist"
                                                                class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm"
                                                            />
                                                        </div>

                                                        <div>
                                                            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                                                                Genre
                                                            </label>
                                                            <select
                                                                name="genre_id"
                                                                x-model="genre"
                                                                class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm"
                                                            >
                                                                <option value="">-- Genre (optional) --</option>
                                                                @foreach($genres as $g)
                                                                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="grid gap-3 md:grid-cols-3">
                                                        <div class="md:col-span-2">
                                                            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                                                                Duration
                                                            </label>
                                                            <div class="grid grid-cols-2 gap-2">
                                                                <input
                                                                    name="duration_minutes"
                                                                    type="number"
                                                                    min="0"
                                                                    x-model.number="minutes"
                                                                    placeholder="Minutes"
                                                                    class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm"
                                                                />
                                                                <input
                                                                    name="duration_seconds"
                                                                    type="number"
                                                                    min="0"
                                                                    max="59"
                                                                    x-model.number="seconds"
                                                                    placeholder="Seconds"
                                                                    class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm"
                                                                />
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                                                                Release Year
                                                            </label>
                                                            <input
                                                                name="release_year"
                                                                type="number"
                                                                min="1900"
                                                                max="2100"
                                                                x-model="year"
                                                                class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm"
                                                            />
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                                                            Notes
                                                        </label>
                                                        <textarea
                                                            name="notes"
                                                            x-model="notes"
                                                            rows="2"
                                                            class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm"
                                                        ></textarea>
                                                    </div>

                                                    <div class="mt-3 flex items-center gap-2">
                                                        <button
                                                            type="button"
                                                            @click="open=false"
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
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No songs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination with query params kept --}}
            <div class="border-t border-zinc-200/70 bg-zinc-50/80 px-4 py-3 dark:border-zinc-800 dark:bg-zinc-900/80">
                {{ $songs->appends(request()->query())->links() }}
            </div>
        </section>

        {{-- Bottom: Add New Song (same card style, 2 rows layout) --}}
        <section class="rounded-2xl border border-zinc-200/80 bg-white/95 p-5 shadow-sm dark:border-zinc-700/70 dark:bg-zinc-900/80">
            <h2 class="mb-3 text-sm font-semibold tracking-wide uppercase text-gray-700 dark:text-gray-200">
                Add New Song
            </h2>

            <form method="POST" action="{{ route('songs.store') }}" class="space-y-4">
                @csrf

                {{-- Row 1: Title (60%) + Artist (40%) --}}
                <div class="grid grid-cols-5 gap-3">
                    <div class="col-span-3">
                        <label class="mb-1 block text-[11px] font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Title *
                        </label>
                        <input
                            name="title"
                            required
                            placeholder="Title"
                            class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm text-gray-900 dark:text-gray-100"
                        />
                    </div>
                    <div class="col-span-2">
                        <label class="mb-1 block text-[11px] font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Artist
                        </label>
                        <input
                            name="artist"
                            placeholder="Artist"
                            class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm text-gray-900 dark:text-gray-100"
                        />
                    </div>
                </div>

                {{-- Row 2: Minutes, Seconds, Genre, Release Year (25% each) --}}
                <div class="grid grid-cols-4 gap-3">
                    <div>
                        <label class="mb-1 block text-[11px] font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Minutes
                        </label>
                        <input
                            name="duration_minutes"
                            type="number"
                            min="0"
                            placeholder="Min"
                            class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm text-gray-900 dark:text-gray-100"
                        />
                    </div>
                    <div>
                        <label class="mb-1 block text-[11px] font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Seconds
                        </label>
                        <input
                            name="duration_seconds"
                            type="number"
                            min="0"
                            max="59"
                            placeholder="Sec"
                            class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm text-gray-900 dark:text-gray-100"
                        />
                    </div>
                    <div>
                        <label class="mb-1 block text-[11px] font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Genre
                        </label>
                        <select
                            name="genre_id"
                            class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm text-gray-900 dark:text-gray-100"
                        >
                            <option value="">-- Genre (optional) --</option>
                            @foreach($genres as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-[11px] font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Release Year
                        </label>
                        <input
                            name="release_year"
                            type="number"
                            min="1900"
                            max="2100"
                            placeholder="Year"
                            class="w-full rounded-lg border border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/80 p-2 text-sm text-gray-900 dark:text-gray-100"
                        />
                    </div>
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900"
                    >
                        Add Song
                    </button>
                </div>
            </form>
        </section>

    </div>
</x-layouts.app.sidebar>