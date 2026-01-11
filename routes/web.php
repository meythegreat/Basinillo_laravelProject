<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\GenreController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;

Route::get('/home', fn () => view('welcome'))->name('home');

Route::get('/students/dashboard', [StudentController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('students.dashboard');

// Student routes - CRUD operations
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
});

// Course routes - CRUD operations
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    // Music library
    Route::get('/', [SongController::class,'index'])->name('dashboard');
    Route::resource('songs', SongController::class)->except(['create','show','edit']);
    Route::resource('genres', GenreController::class)->only(['index','store','update','destroy']);

    Route::get('/songs/trash', [SongController::class, 'trash'])->name('songs.trash');
    Route::post('/songs/{id}/restore', [SongController::class, 'restore'])->name('songs.restore');
    Route::delete('/songs/{id}/force-delete', [SongController::class, 'forceDelete'])->name('songs.forceDelete');

    Route::get('/songs/export/pdf', [SongController::class, 'exportPdf'])
    ->name('songs.export.pdf')
    ->middleware('auth');

});

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    Auth::login($user);
    return redirect()->route('dashboard'); 
});

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

require __DIR__.'/auth.php';