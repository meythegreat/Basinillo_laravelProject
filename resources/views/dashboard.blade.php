<x-layouts.app.sidebar :title="'Music Dashboard'">
    <div class="w-full p-6 text-gray-900 dark:text-gray-100 space-y-8">

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
                        <a href="{{ route('songs.export.pdf', request()->query()) }}" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">Export PDF</a>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full table-auto text-sm">
                    <thead class="bg-zinc-100/80 text-left text-xs uppercase tracking-wide text-gray-600 dark:bg-zinc-900/80 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">Cover</th>
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
                                    @if($song->photo)
                                        <img
                                            src="{{ asset('storage/' . $song->photo) }}"
                                            alt="Album cover"
                                            class="h-12 w-12 rounded-lg object-cover shadow"
                                        >
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-xs font-semibold text-zinc-600 dark:text-zinc-300">
                                            {{ strtoupper(substr($song->title, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
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
                                <div class="flex items-center gap-2">
                                    {{-- Edit Button --}}
                                    <button
                                        @click="open=true"
                                        class="group inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-600 transition-all hover:bg-indigo-600 hover:text-white dark:bg-indigo-500/10 dark:text-indigo-400 dark:hover:bg-indigo-500 dark:hover:text-white"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 transition-transform group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>

                                    {{-- Delete Button --}}
                                    <form
                                        method="POST"
                                        action="{{ route('songs.destroy', $song) }}"
                                        onsubmit="return confirm('Move this song to the trash?');"
                                        class="inline-block"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="group inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-600 transition-all hover:bg-rose-600 hover:text-white dark:bg-rose-500/10 dark:text-rose-400 dark:hover:bg-rose-500 dark:hover:text-white"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>

                                        {{-- Edit modal --}}
                                        <div
                                            x-show="open"
                                            x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                                        >
                                            <div
                                                @click.away="open=false"
                                                class="w-full max-w-xl rounded-2xl border border-zinc-200/80
                                                    bg-white p-6 shadow-xl
                                                    dark:border-zinc-700/70 dark:bg-zinc-900 dark:text-gray-100"
                                            >
                                                {{-- Header --}}
                                                <div class="mb-4 flex items-start justify-between">
                                                    <div>
                                                        <h3 class="text-lg font-semibold text-left">Edit Song</h3>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            Update song details or replace the cover image.
                                                        </p>
                                                    </div>
                                                    <button
                                                        type="button"
                                                        @click="open=false"
                                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                                                    >
                                                        ✕
                                                    </button>
                                                </div>

                                                <form
                                                    action="{{ route('songs.update', $song) }}"
                                                    method="POST"
                                                    enctype="multipart/form-data"
                                                    class="space-y-4"
                                                >
                                                    @csrf
                                                    @method('PUT')

                                                    {{-- Title --}}
                                                    <div>
                                                        <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                                                            Title *
                                                        </label>
                                                        <input
                                                            name="title"
                                                            x-model="title"
                                                            required
                                                            class="w-full rounded-lg border border-gray-300
                                                                bg-gray-50 p-2 text-sm
                                                                dark:border-zinc-700 dark:bg-zinc-800/80"
                                                        />
                                                    </div>

                                                    {{-- Artist & Genre --}}
                                                    <div class="grid grid-cols-2 gap-3">
                                                        <div>
                                                            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                                                                Artist
                                                            </label>
                                                            <input
                                                                name="artist"
                                                                x-model="artist"
                                                                class="w-full rounded-lg border border-gray-300
                                                                    bg-gray-50 p-2 text-sm
                                                                    dark:border-zinc-700 dark:bg-zinc-800/80"
                                                            />
                                                        </div>

                                                        <div>
                                                            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                                                                Genre
                                                            </label>
                                                            <select
                                                                name="genre_id"
                                                                x-model="genre"
                                                                class="w-full rounded-lg border border-gray-300
                                                                    bg-gray-50 p-2 text-sm
                                                                    dark:border-zinc-700 dark:bg-zinc-800/80"
                                                            >
                                                                <option value="">No genre</option>
                                                                @foreach($genres as $g)
                                                                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- Duration & Year --}}
                                                    <div class="grid grid-cols-3 gap-3">
                                                        <div class="col-span-2">
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
                                                                    class="w-full rounded-lg border border-gray-300
                                                                        bg-gray-50 p-2 text-sm
                                                                        dark:border-zinc-700 dark:bg-zinc-800/80"
                                                                />
                                                                <input
                                                                    name="duration_seconds"
                                                                    type="number"
                                                                    min="0"
                                                                    max="59"
                                                                    x-model.number="seconds"
                                                                    placeholder="Seconds"
                                                                    class="w-full rounded-lg border border-gray-300
                                                                        bg-gray-50 p-2 text-sm
                                                                        dark:border-zinc-700 dark:bg-zinc-800/80"
                                                                />
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                                                                Year
                                                            </label>
                                                            <input
                                                                name="release_year"
                                                                type="number"
                                                                min="1900"
                                                                max="2100"
                                                                x-model="year"
                                                                class="w-full rounded-lg border border-gray-300
                                                                    bg-gray-50 p-2 text-sm
                                                                    dark:border-zinc-700 dark:bg-zinc-800/80"
                                                            />
                                                        </div>
                                                    </div>

                                                    {{-- Notes --}}
                                                    <div>
                                                        <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                                                            Notes
                                                        </label>
                                                        <textarea
                                                            name="notes"
                                                            x-model="notes"
                                                            rows="2"
                                                            class="w-full rounded-lg border border-gray-300
                                                                bg-gray-50 p-2 text-sm
                                                                dark:border-zinc-700 dark:bg-zinc-800/80"
                                                        ></textarea>
                                                    </div>

                                                    {{-- Current Photo + Remove --}}
                                                    @if($song->photo)
                                                        <div class="flex items-center gap-3">
                                                            <img
                                                                src="{{ asset('storage/'.$song->photo) }}"
                                                                class="h-16 w-16 rounded-lg object-cover border"
                                                                alt="Current cover"
                                                            >
                                                            <label class="flex items-center gap-2 text-xs text-rose-600">
                                                                <input
                                                                    type="checkbox"
                                                                    name="remove_photo"
                                                                    value="1"
                                                                    class="rounded border-gray-300"
                                                                >
                                                                Remove current photo
                                                            </label>
                                                        </div>
                                                    @endif

                                                    {{-- Drag & Drop Upload --}}
                                                    <div
                                                        x-data="{ drag:false }"
                                                        @dragover.prevent="drag=true"
                                                        @dragleave.prevent="drag=false"
                                                        @drop.prevent="drag=false"
                                                        :class="drag
                                                            ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20'
                                                            : 'border-zinc-300 dark:border-zinc-700'"
                                                        class="relative rounded-lg border-2 border-dashed p-4 text-center text-xs transition"
                                                    >
                                                        <input
                                                            type="file"
                                                            name="photo"
                                                            accept="image/png,image/jpeg"
                                                            class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                                                        >
                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            Drag & drop a new cover here, or click to browse
                                                        </p>
                                                    </div>

                                                    {{-- Actions --}}
                                                    <div class="mt-4 flex items-center gap-2">
                                                        <button
                                                            type="button"
                                                            @click="open=false"
                                                            class="rounded-lg border border-zinc-300
                                                                bg-zinc-100 px-4 py-1.5 text-xs font-medium
                                                                text-gray-700 hover:bg-gray-900
                                                                dark:border-zinc-700 dark:bg-zinc-800 dark:text-gray-200"
                                                        >
                                                            Cancel
                                                        </button>
                                                        <button
                                                            type="submit"
                                                            class="ml-auto rounded-lg bg-indigo-600
                                                                px-4 py-1.5 text-xs font-semibold
                                                                text-white hover:bg-indigo-700"
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

{{-- Add New Song Section --}}
<section class="rounded-xl bg-white dark:bg-zinc-800 shadow-xl border border-zinc-200 dark:border-zinc-700/50 p-6">
    <header class="flex items-center gap-3 mb-6">
        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </div>
        <div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Add New Track</h2>
            <p class="text-xs text-gray-500 dark:text-zinc-400">
                Fill in the details to add a song to your library.
            </p>
        </div>
    </header>

    <form method="POST" action="{{ route('songs.store') }}" enctype="multipart/form-data" class="grid gap-6">
        @csrf

        {{-- Title & Artist --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-1 text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-zinc-400">
                    Title <span class="text-rose-500">*</span>
                </label>
                <input
                    name="title"
                    required
                    placeholder="e.g. Bohemian Rhapsody"
                    class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700
                           bg-gray-50 dark:bg-zinc-900/50
                           p-3 text-gray-900 dark:text-white
                           placeholder-gray-400 dark:placeholder-zinc-600
                           focus:ring-2 focus:ring-indigo-500"
                />
            </div>

            <div>
                <label class="block mb-1 text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-zinc-400">
                    Artist
                </label>
                <input
                    name="artist"
                    placeholder="e.g. Queen"
                    class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700
                           bg-gray-50 dark:bg-zinc-900/50
                           p-3 text-gray-900 dark:text-white
                           placeholder-gray-400 dark:placeholder-zinc-600
                           focus:ring-2 focus:ring-indigo-500"
                />
            </div>
        </div>

        {{-- Genre, Year, Duration --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block mb-1 text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-zinc-400">
                    Genre
                </label>
                <select
                    name="genre_id"
                    class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700
                           bg-gray-50 dark:bg-zinc-900/50
                           p-3 text-gray-900 dark:text-white
                           focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="">Select Genre</option>
                    @foreach($genres as $g)
                        <option value="{{ $g->id }}">{{ $g->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-zinc-400">
                    Release Year
                </label>
                <input
                    name="release_year"
                    type="number"
                    min="1900"
                    max="2100"
                    placeholder="1975"
                    class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700
                           bg-gray-50 dark:bg-zinc-900/50
                           p-3 text-gray-900 dark:text-white
                           placeholder-gray-400 dark:placeholder-zinc-600
                           focus:ring-2 focus:ring-indigo-500"
                />
            </div>

            <div>
                <label class="block mb-1 text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-zinc-400">
                    Duration
                </label>
                <div class="flex gap-2">
                    <input
                        name="duration_minutes"
                        type="number"
                        min="0"
                        placeholder="Min"
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700
                               bg-gray-50 dark:bg-zinc-900/50
                               p-3 text-center text-gray-900 dark:text-white
                               placeholder-gray-400 dark:placeholder-zinc-600
                               focus:ring-2 focus:ring-indigo-500"
                    />
                    <input
                        name="duration_seconds"
                        type="number"
                        min="0"
                        max="59"
                        placeholder="Sec"
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700
                               bg-gray-50 dark:bg-zinc-900/50
                               p-3 text-center text-gray-900 dark:text-white
                               placeholder-gray-400 dark:placeholder-zinc-600
                               focus:ring-2 focus:ring-indigo-500"
                    />
                </div>
            </div>
        </div>

        {{-- File Upload + Submit --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
            <div>
                <label class="block mb-1 text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-zinc-400">
                    Cover Art
                </label>
                <input
                    type="file"
                    name="photo"
                    accept="image/png,image/jpeg"
                    class="block w-full text-sm text-gray-600 dark:text-zinc-400
                           file:mr-4 file:py-2.5 file:px-4
                           file:rounded-full file:border-0
                           file:text-xs file:font-bold
                           file:bg-zinc-200 dark:file:bg-zinc-700
                           file:text-indigo-600 dark:file:text-indigo-400
                           hover:file:bg-zinc-300 dark:hover:file:bg-zinc-600"
                />
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-xl
                           bg-indigo-600 px-8 py-3 font-bold text-white
                           shadow-lg shadow-indigo-500/30
                           hover:bg-indigo-500 focus:ring-2 focus:ring-indigo-400"
                >
                    Add to Library
                </button>
            </div>
        </div>
    </form>
</section>

    </div>
</x-layouts.app.sidebar>