<x-layouts.app.sidebar :title="'Trash'">
    <div class="w-full p-6 text-gray-900 dark:text-gray-100 space-y-8">

        {{-- Page header --}}
        {{-- Rose/Red gradient to signify "Trash/Warning" context --}}
        <header class="rounded-2xl bg-gradient-to-r from-rose-500 via-red-500 to-orange-500 px-6 py-5 text-white shadow-lg">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight">Recycle Bin</h1>
                    <p class="mt-1 text-sm text-rose-100">
                        View deleted songs. Restore them to your library or permanently remove them.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <span class="inline-flex items-center gap-1 rounded-full bg-white/10 px-3 py-1 backdrop-blur">
                        <span class="font-semibold">{{ $songs->total() }}</span> deleted items
                    </span>
                </div>
            </div>

            @if(session('success'))
                <div class="mt-4 rounded-lg bg-white/90 text-rose-700 px-4 py-3 text-sm shadow-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
        </header>

        {{-- Top: quick stats --}}
        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {{-- Total Trashed --}}
            <article class="flex items-center gap-3 rounded-2xl border border-zinc-200/80 bg-white/95 p-4 shadow-sm dark:border-zinc-700/70 dark:bg-zinc-900/80">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-500/10 text-rose-500 dark:bg-rose-400/10 dark:text-rose-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Items in Trash
                    </p>
                    <p class="text-2xl font-semibold">
                        {{ $songs->total() }}
                    </p>
                </div>
            </article>

            {{-- Deleted Today (Example Logic) --}}
            <article class="flex items-center gap-3 rounded-2xl border border-zinc-200/80 bg-white/95 p-4 shadow-sm dark:border-zinc-700/70 dark:bg-zinc-900/80">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500/10 text-orange-500 dark:bg-orange-400/10 dark:text-orange-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Deleted Today
                    </p>
                    <p class="text-2xl font-semibold">
                        {{-- Assuming you might want to filter this in controller, or just use collection filter here for display --}}
                        {{ $songs->filter(fn($s) => $s->deleted_at->isToday())->count() }}
                    </p>
                </div>
            </article>
        </section>

        {{-- Middle: Trash Table --}}
        <section class="rounded-2xl border border-zinc-200/80 bg-white/95 shadow-sm dark:border-zinc-700/70 dark:bg-zinc-900/80 overflow-hidden">
            <div class="border-b border-zinc-200/70 bg-zinc-50/80 px-4 py-3 text-sm dark:border-zinc-700/70 dark:bg-zinc-900/70">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Deleted Songs</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Find songs to restore or remove forever.
                        </p>
                    </div>

                    {{-- Search Form --}}
                    <form method="GET" action="{{ url()->current() }}" class="flex gap-2">
                        <div>
                            <label class="block text-[11px] font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">
                                Search Trash
                            </label>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Title or Artist..."
                                class="w-48 rounded-lg border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800/80 px-2.5 py-1.5 text-xs text-gray-900 dark:text-gray-100"
                            />
                        </div>
                        <div class="self-end pb-0.5">
                            <button type="submit" class="rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">
                                Apply
                            </button>
                            @if(request()->filled('search'))
                                <a href="{{ url()->current() }}" class="ml-1 rounded-lg border border-zinc-300 bg-zinc-100 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-zinc-200 dark:border-zinc-700 dark:bg-zinc-800 dark:text-gray-200 dark:hover:bg-zinc-700">
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
                            <th class="px-4 py-3">Deleted At</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200/70 bg-white/80 dark:divide-zinc-800 dark:bg-transparent">
                        @forelse($songs as $song)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/60 transition-colors group">
                                <td class="px-4 py-3 align-middle font-medium text-gray-600 dark:text-gray-300">
                                    {{ $song->title }}
                                </td>
                                <td class="px-4 py-3 align-middle text-gray-500 dark:text-gray-400">
                                    {{ $song->artist ?? 'Unknown' }}
                                </td>
                                <td class="px-4 py-3 align-middle text-xs text-gray-500">
                                    <span class="block text-gray-700 dark:text-gray-300 font-medium">
                                        {{ $song->deleted_at->format('M d, Y') }}
                                    </span>
                                    <span class="text-[10px] uppercase">
                                        {{ $song->deleted_at->format('h:i A') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 align-middle text-right">
                                    <div class="inline-flex items-center justify-end gap-3 opacity-80 group-hover:opacity-100 transition-opacity">
                                        
                                        {{-- Restore Action --}}
                                        <form method="POST" action="{{ route('songs.restore', $song->id) }}">
                                            @csrf
                                            <button 
                                                type="submit" 
                                                class="flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium text-emerald-600 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-900/30 transition"
                                                title="Restore to Library"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                                </svg>
                                                Restore
                                            </button>
                                        </form>

                                        {{-- Permanent Delete Action --}}
                                        <form 
                                            method="POST"
                                            action="{{ route('songs.forceDelete', $song->id) }}"
                                            onsubmit="return confirm('⚠️ Are you sure?\n\nThis will permanently delete \'{{ $song->title }}\'. This action cannot be undone.');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-900/30 transition"
                                                title="Delete Forever"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium">Your trash is empty!</p>
                                        <p class="text-xs text-gray-400">Items you delete will show up here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="border-t border-zinc-200/70 bg-zinc-50/80 px-4 py-3 dark:border-zinc-800 dark:bg-zinc-900/80">
                {{ $songs->appends(request()->query())->links() }}
            </div>
        </section>

    </div>
</x-layouts.app.sidebar>