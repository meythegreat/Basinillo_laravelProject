<x-layouts.app.sidebar :title="'Music Dashboard'">
    <div class="mx-auto max-w-7xl p-6 text-black dark:text-white">
        {{-- Page header --}}
        <header class="mb-6">
            <h1 class="text-3xl font-extrabold tracking-tight">Music Dashboard</h1>
            @if(session('success'))
                <div class="mt-3 rounded bg-green-600/10 p-3 text-sm text-green-600">{{ session('success') }}</div>
            @endif
        </header>

        {{-- Statistic cards --}}
        <section class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

            {{-- Total Songs --}}
            <div class="p-4 rounded-lg bg-white/5 shadow-sm flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 19V6l12-2v13" />
                    <circle cx="6" cy="18" r="3" />
                    <circle cx="18" cy="16" r="3" />
                </svg>

                <div>
                    <div class="text-sm">Total Songs</div>
                    <div class="text-2xl font-semibold">{{ $totalSongs ?? 0 }}</div>
                </div>
            </div>

            {{-- Total Genres --}}
            <div class="p-4 rounded-lg bg-white/5 shadow-sm flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M7 7h.01M4 4h16v16H4V4zm3 13.5h10M7 10h10M7 13h10" />
                </svg>

                <div>
                    <div class="text-sm">Total Genres</div>
                    <div class="text-2xl font-semibold">{{ $totalGenres ?? 0 }}</div>
                </div>
            </div>

            {{-- Total Duration --}}
            <div class="p-4 rounded-lg bg-white/5 shadow-sm flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 6v6l4 2m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <div>
                    <div class="text-sm">Total Duration</div>
                    <div class="text-2xl font-semibold">
                        {{ intdiv($totalDuration ?? 0, 60) }}m {{ ($totalDuration ?? 0) % 60 }}s
                    </div>
                </div>
            </div>

        </section>

        {{-- Add song form --}}
        <section class="mb-6">
        <div class="rounded-lg bg-white/5 p-4">
            <form method="POST" action="{{ route('songs.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            @csrf
            <input name="title" placeholder="Title" required class="w-full rounded border-none bg-white/10 p-2" />
            <input name="artist" placeholder="Artist" class="w-full rounded border-none bg-white/10 p-2" />
            <select name="genre_id" class="w-full rounded border-none bg-white/10 p-2">
                <option value="">-- Genre (optional) --</option>
                @foreach($genres as $g) <option value="{{ $g->id }}">{{ $g->name }}</option> @endforeach
            </select>

            <input name="duration_minutes" type="number" min="0" placeholder="Minutes" class="w-full rounded border-none bg-white/10 p-2" />
            <input name="duration_seconds" type="number" min="0" max="59" placeholder="Seconds" class="w-full rounded border-none bg-white/10 p-2" />
            <input name="release_year" type="number" min="1900" max="2100" placeholder="Release Year" class="w-full rounded border-none bg-white/10 p-2" />

            <div class="flex items-center md:col-span-3">
                <button type="submit" class="ml-auto inline-flex items-center rounded px-4 py-2 font-medium bg-indigo-600 text-white">Add Song</button>
            </div>
            </form>
        </div>
        </section>

        {{-- Songs table --}}
        <section class="rounded-lg bg-white/5 shadow-sm overflow-hidden">
            <table class="min-w-full table-auto">
                <thead class="bg-white/3 text-left">
                    <tr>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Artist</th>
                        <th class="px-4 py-3">Genre</th>
                        <th class="px-4 py-3">Duration</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-transparent divide-y">
                @forelse($songs as $song)
                    <tr>
                    <td class="px-4 py-3">{{ $song->title ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $song->artist ?? 'N/A' }}</td>
                    <td class="px-4 py-3">{{ $song->genre->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3">
                        @if($song->duration_seconds)
                        {{ intdiv($song->duration_seconds,60) }}m {{ $song->duration_seconds % 60 }}s
                        @else
                        N/A
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div x-data="{ open:false, minutes: {{ (int) ($song->duration_seconds ? intdiv($song->duration_seconds,60) : 0) }}, seconds: {{ (int) ($song->duration_seconds ? $song->duration_seconds % 60 : 0) }}, title:@js($song->title), artist:@js($song->artist), genre:{{ $song->genre_id ?? 'null' }}, year:{{ $song->release_year ?? 'null' }}, notes:@js($song->notes) }" class="relative">
                        <button @click="open=true" class="text-sm underline mr-2">Edit</button>

                        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                            <div @click.away="open=false" class="w-full max-w-2xl rounded bg-zinc-900 p-4">
                            <h3 class="text-lg font-semibold mb-3">Edit Song</h3>
                            <form action="{{ route('songs.update', $song) }}" method="POST" class="grid gap-2">
                            @csrf
                            @method('PUT')

                            <input name="title" x-model="title" required class="w-full rounded p-2 bg-white/5" />
                            <input name="artist" x-model="artist" class="w-full rounded p-2 bg-white/5" />
                            <select name="genre_id" x-model="genre" class="w-full rounded p-2 bg-white/5">
                                <option value="">-- Genre (optional) --</option>
                                @foreach($genres as $g) <option value="{{ $g->id }}">{{ $g->name }}</option> @endforeach
                            </select>

                            <div class="grid grid-cols-2 gap-2">
                                <input name="duration_minutes" type="number" min="0" x-model.number="minutes" placeholder="Minutes" class="w-full rounded p-2 bg-white/5" />
                                <input name="duration_seconds" type="number" min="0" max="59" x-model.number="seconds" placeholder="Seconds" class="w-full rounded p-2 bg-white/5" />
                            </div>

                            <input name="release_year" type="number" min="1900" max="2100" x-model="year" class="w-full rounded p-2 bg-white/5" />
                            <textarea name="notes" x-model="notes" class="w-full rounded p-2 bg-white/5" rows="2"></textarea>

                            <div class="flex gap-2 mt-2">
                                <button type="button" @click="open=false" class="px-3 py-1 rounded border">Cancel</button>
                                <button type="submit" class="ml-auto px-4 py-1 rounded bg-indigo-600 text-white">Save</button>
                            </div>
                            </form>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('songs.destroy', $song) }}" onsubmit="return confirm('Delete this song?');" class="inline-block">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-sm underline">Delete</button>
                        </form>
                        </div>
                    </td>
                    </tr>
                @empty
                    <tr>
                    <td colspan="5" class="px-4 py-6 text-center">No songs yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="p-4">
                {{ $songs->links() }}
            </div>
        </section>
    </div>
</x-layouts.app.sidebar>