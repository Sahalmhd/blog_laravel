<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }




    public function signup()
    {
        return view('signup');
    }

    public function auth(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication successful, get the authenticated user
            $user = Auth::user();

            if ($user->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('user.dashboard');
            }
        }

        // Authentication failed, redirect back with error
        return back()->withErrors(['message' => 'Invalid credentials'])->withInput();
    }


    public function logout(Request $request)
    {
        Auth::logout(); // Logout the user

        // Redirect to the login page after logout
        return redirect()->route('index');
    }
}
