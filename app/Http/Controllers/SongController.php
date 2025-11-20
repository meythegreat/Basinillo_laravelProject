<?php
namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Genre;
use Illuminate\Http\Request;

class SongController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $songs = Song::with('genre')->latest()->paginate(10);
        $genres = Genre::orderBy('name')->get();
        $totalSongs = Song::count();
        $totalGenres = Genre::count();
        $totalDuration = Song::sum('duration_seconds') ?? 0;
        return view('dashboard', compact('songs','genres','totalSongs','totalGenres','totalDuration'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'title' => 'required|string|max:255',
            'artist' => 'nullable|string|max:255',
            'genre_id' => 'nullable|exists:genres,id',
            'duration_minutes' => 'nullable|integer|min:0',
            'duration_seconds' => 'nullable|integer|min:0|max:59',
            'release_year' => 'nullable|digits:4|integer',
            'notes' => 'nullable|string',
        ]);

        $minutes = (int) $r->input('duration_minutes', 0);
        $secondsPart = (int) $r->input('duration_seconds', 0);
        $totalSeconds = ($minutes * 60) + $secondsPart;

        Song::create(array_merge(
            $r->only('title','artist','genre_id','release_year','notes'),
            ['duration_seconds' => $totalSeconds ?: null]
        ));

        return back()->with('success','Song added.');
    }

    public function update(Request $r, Song $song)
    {
        $r->validate([
            'title' => 'required|string|max:255',
            'artist' => 'nullable|string|max:255',
            'genre_id' => 'nullable|exists:genres,id',
            'duration_minutes' => 'nullable|integer|min:0',
            'duration_seconds' => 'nullable|integer|min:0|max:59',
            'release_year' => 'nullable|digits:4|integer',
            'notes' => 'nullable|string',
        ]);

        $minutes = (int) $r->input('duration_minutes', 0);
        $secondsPart = (int) $r->input('duration_seconds', 0);
        $totalSeconds = ($minutes * 60) + $secondsPart;

        $song->update(array_merge(
            $r->only('title','artist','genre_id','release_year','notes'),
            ['duration_seconds' => $totalSeconds ?: null]
        ));

        return back()->with('success','Song updated.');
    }

    public function destroy(Song $song)
    {
        $song->delete();
        return back()->with('success','Song deleted.');
    }
}