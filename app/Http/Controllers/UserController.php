<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
class UserController extends Controller
{
    public function show()
    {

        $posts = Post::with('user:id,name')->get();

        $user = Auth::user();
        return view('users.dashboard', ['username' => $user->name,'posts' => $posts]);
    }
    public function showblogpg()
    {
        $user = Auth::user();

        return view('users.postblog', ['username' => $user->name,]);
    }

    public function post_blog(Request $request)
    {
        // Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Create a new post
        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->user_id = auth()->id(); // Associate the post with the currently authenticated user
        $post->save();

        // Redirect back with success message
        return redirect()->route('user.dashboard')->with('success', 'Blog post created successfully!');
    }
}
