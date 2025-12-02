<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ✅ UPDATED: Search + pagination + withQueryString()
    public function index(Request $request)
    {
        $genresQuery = Genre::withCount('songs')
            ->orderBy('name');

        // Search by genre name
        if ($search = $request->input('search')) {
            $genresQuery->where('name', 'like', "%{$search}%");
        }

        // Use paginate so search works with pages
        $genres = $genresQuery
            ->paginate(10)
            ->withQueryString(); // keeps ?search= on next pages

        return view('genres.index', compact('genres'));
    }

    public function store(Request $r)
    {
        try {
            $r->validate([
                'name' => 'required|string|max:255|unique:genres,name',
                'description' => 'nullable|string'
            ]);

            Genre::create($r->only('name','description'));

            return back()->with('success','Genre created.');
        } catch (\Throwable $e) {
            \Log::error('Genre store failed: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors('Failed to create genre. Check logs for details.');
        }
    }

    public function update(Request $r, Genre $genre)
    {
        $r->validate([
            'name' => "required|string|max:255|unique:genres,name,{$genre->id}",
            'description' => 'nullable|string',
        ]);

        $genre->update($r->only('name','description'));

        return back()->with('success','Genre updated.');
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();

        return back()->with('success','Genre deleted.');
    }
}