<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        // Make sure your blade file is actually at resources/views/auth/login.blade.php
        // If it's just in resources/views/login.blade.php, change this to view('login')
        return view('auth.login'); 
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        // 1. Validate the inputs
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Attempt to log the user in
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect to dashboard (or wherever they wanted to go)
            return redirect()->intended('dashboard');
        }

        // 3. If login fails, go back with an error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}