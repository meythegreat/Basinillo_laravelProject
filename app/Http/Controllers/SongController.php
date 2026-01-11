<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SongController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $songsQuery = Song::with('genre');

        if ($search = $request->input('search')) {
            $songsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('artist', 'like', "%{$search}%");
            });
        }

        if ($genreId = $request->input('genre')) {
            $songsQuery->where('genre_id', $genreId);
        }

        $songs = $songsQuery
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalSongs    = Song::count();
        $totalGenres   = Genre::count();
        $totalDuration = Song::sum('duration_seconds') ?? 0;

        $genres = Genre::orderBy('name')->get();

        return view('dashboard', compact(
            'songs', 'genres', 'totalSongs', 'totalGenres', 'totalDuration'
        ));
    }

    public function store(Request $r)
    {
        $validated = $r->validate([
            'title' => 'required|string|max:255',
            'artist' => 'nullable|string|max:255',
            'genre_id' => 'nullable|exists:genres,id',
            'duration_minutes' => 'nullable|integer|min:0',
            'duration_seconds' => 'nullable|integer|min:0|max:59',
            'release_year' => 'nullable|digits:4|integer',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $minutes = (int) $r->input('duration_minutes', 0);
        $seconds = (int) $r->input('duration_seconds', 0);
        $totalSeconds = ($minutes * 60) + $seconds;

        if ($r->hasFile('photo')) {
            $validated['photo'] = $r->file('photo')->store('songs', 'public');
        }

        Song::create(array_merge(
            $r->only('title','artist','genre_id','release_year','notes'),
            [
                'duration_seconds' => $totalSeconds ?: null,
                'photo' => $validated['photo'] ?? null,
            ]
        ));

        return back()->with('success', 'Song added.');
    }

    public function update(Request $r, Song $song)
    {
        $validated = $r->validate([
            'title' => 'required|string|max:255',
            'artist' => 'nullable|string|max:255',
            'genre_id' => 'nullable|exists:genres,id',
            'duration_minutes' => 'nullable|integer|min:0',
            'duration_seconds' => 'nullable|integer|min:0|max:59',
            'release_year' => 'nullable|digits:4|integer',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'remove_photo' => 'nullable|boolean',
        ]);

        $minutes = (int) $r->input('duration_minutes', 0);
        $seconds = (int) $r->input('duration_seconds', 0);
        $totalSeconds = ($minutes * 60) + $seconds;

        /* ---------------------------------
        REMOVE CURRENT PHOTO (FIX)
        ----------------------------------*/
        if ($r->boolean('remove_photo') && $song->photo) {
            if (Storage::disk('public')->exists($song->photo)) {
                Storage::disk('public')->delete($song->photo);
            }
            $song->photo = null;
        }

        /* ---------------------------------
        UPLOAD NEW PHOTO (REPLACE)
        ----------------------------------*/
        if ($r->hasFile('photo')) {
            if ($song->photo && Storage::disk('public')->exists($song->photo)) {
                Storage::disk('public')->delete($song->photo);
            }
            $song->photo = $r->file('photo')->store('songs', 'public');
        }

        /* ---------------------------------
        SAVE OTHER FIELDS
        ----------------------------------*/
        $song->update([
            'title' => $validated['title'],
            'artist' => $validated['artist'] ?? null,
            'genre_id' => $validated['genre_id'] ?? null,
            'release_year' => $validated['release_year'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'duration_seconds' => $totalSeconds ?: null,
            'photo' => $song->photo,
        ]);

        return back()->with('success', 'Song updated.');
    }

    public function destroy(Song $song)
    {
        $song->delete();
        return back()->with('success', 'Song moved to trash.');
    }

    public function trash()
    {
        $songs = Song::onlyTrashed()
            ->with('genre')
            ->latest('deleted_at')
            ->paginate(10);

        return view('songs.trash', compact('songs'));
    }

    public function restore($id)
    {
        $song = Song::onlyTrashed()->findOrFail($id);
        $song->restore();

        return back()->with('success', 'Song restored.');
    }

    public function forceDelete($id)
    {
        $song = Song::onlyTrashed()->findOrFail($id);

        if ($song->photo && Storage::disk('public')->exists($song->photo)) {
            Storage::disk('public')->delete($song->photo);
        }

        $song->forceDelete();

        return back()->with('success', 'Song permanently deleted.');
    }

    public function exportPdf(Request $request)
{
    $songsQuery = Song::with('genre');

    if ($search = $request->input('search')) {
        $songsQuery->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('artist', 'like', "%{$search}%");
        });
    }

    if ($genreId = $request->input('genre')) {
        $songsQuery->where('genre_id', $genreId);
    }

    $songs = $songsQuery->orderBy('title')->get();

    $pdf = Pdf::loadView('songs.pdf', [
        'songs' => $songs,
        'generatedAt' => now(),
    ]);

    return $pdf->download(
        'songs_' . now()->format('Ymd_His') . '.pdf'
    );
}
    

}