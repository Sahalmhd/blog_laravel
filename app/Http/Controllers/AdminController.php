<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $users = User::all();
        $count=1;
        if ($user && $user->role === 'admin') {
            // Return the admin dashboard view with the username
            return view('admin.dashboard', ['username' => $user->name,'count' => $count,'users' => $users,]);
        } else {
            // Redirect to login page or unauthorized page
            return redirect()->route('index');
        }    }
}
