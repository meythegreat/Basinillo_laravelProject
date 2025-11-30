<x-layouts.app.sidebar :title="'Genres'">
  <div class="mx-auto max-w-7xl p-6 text-black dark:text-white">
    <header class="mb-6">
      <h1 class="text-3xl font-extrabold tracking-tight">Genres</h1>
      @if(session('success'))
        <div class="mt-3 rounded bg-green-600/10 p-3 text-sm text-green-600">{{ session('success') }}</div>
      @endif
    </header>

    {{-- Add genre form --}}
    <section class="mb-6">
      <div class="rounded-lg bg-white p-4">
        <form method="POST" action="{{ route('genres.store') }}" class="grid md:grid-cols-3 gap-3">
          @csrf
          <input name="name" placeholder="Genre name" required class="w-full rounded border bg-white/10 p-2" />
          <input name="description" placeholder="Short description (optional)" class="w-full rounded border bg-white/10 p-2" />
          <div class="flex items-center">
            <button type="submit" class="ml-auto inline-flex items-center rounded px-4 py-2 font-medium bg-indigo-600 text-white">Add Genre</button>
          </div>
        </form>
      </div>
    </section>

    {{-- Genres table --}}
    <section class="rounded-lg bg-white shadow-sm overflow-hidden">
      <table class="min-w-full table-auto">
        <thead class="bg-white/3 text-left">
          <tr>
            <th class="px-4 py-3">Name</th>
            <th class="px-4 py-3">No. of Songs</th>
            <th class="px-4 py-3">Actions</th>
          </tr>
        </thead>

        <tbody class="bg-transparent divide-y">
          @forelse($genres as $genre)
            <tr>
              <td class="px-4 py-3">{{ $genre->name }}</td>
              <td class="px-4 py-3">{{ $genre->songs_count ?? 0 }}</td>
              <td class="px-4 py-3">
                <div x-data="{ open:false, name:@js($genre->name), description:@js($genre->description) }" class="relative">
                  <button @click="open=true" class="text-sm underline mr-2">Edit</button>

                  <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                    <div @click.away="open=false" class="w-full max-w-lg rounded bg-zinc-900 p-4">
                      <h3 class="text-lg font-semibold mb-3 text-white">Edit Genre</h3>
                        <form action="{{ route('genres.update', $genre) }}" method="POST" class="grid gap-2">
                            @csrf
                            @method('PUT')

                            <input name="name" x-model="name" required class="w-full rounded p-2 bg-white" />
                            <textarea name="description" x-model="description" class="w-full rounded p-2 bg-white" rows="3"></textarea>

                            <div class="flex gap-2 mt-2">
                                <button type="button" @click="open = false" class="px-3 py-1 rounded border text-white bg-red-500">Cancel</button>
                                <button type="submit" class="ml-auto px-4 py-1 rounded border bg-green-400">Save</button>
                            </div>
                        </form>
                    </div>
                  </div>

                  {{-- Delete button --}}
                  <form method="POST" action="{{ route('genres.destroy', $genre) }}" onsubmit="return confirm('Delete this genre?');" class="inline-block">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm underline">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="px-4 py-6 text-center">No genres yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </section>
  </div>
</x-layouts.app.sidebar>
