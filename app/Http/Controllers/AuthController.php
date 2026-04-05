<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Staff;

class AuthController extends Controller
{
    public function dashboard() {
        $studentCount = Student::count();
        $staffCount   = Staff::count();

        return view('dashboard', compact('studentCount', 'staffCount'));
    }

    // Show Login Page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('dashboard')
                ->with('success', 'Login successful');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ])->withInput();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logged out');
    }
}
