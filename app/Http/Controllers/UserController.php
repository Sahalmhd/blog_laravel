<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function show()
    {

        $posts = Post::with('user:id,name')->get();

        $user = Auth::user();
        return view('users.dashboard', ['username' => $user->name, 'posts' => $posts]);
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
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:4096', // Validate image type and size
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            Storage::disk('images')->put($imageName, file_get_contents($image), 'public');
        } else {
            $imageName = null; // Set default image name if no image is uploaded
        }

        // Create a new post
        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->image = $imageName; // Save image path in the database
        $post->user_id = auth()->id(); // Associate the post with the currently authenticated user
        $post->save();

        // Redirect back with success message
        return redirect()->route('user.dashboard')->with('success', 'Blog post created successfully!');
    }

    public function edit_blog_page($id)
    {
        $user = Auth::user();
        // Find the post by its ID
        $post = Post::find($id);

        // Check if the post exists
        if (!$post) {
            // If the post does not exist, redirect back with an error message
            return redirect()->back()->with('error', 'Post not found.');
        }

        // Check if the currently authenticated user is the owner of the post
        if ($post->user_id !== auth()->id()) {
            // If the user is not the owner of the post, redirect back with an error message
            return redirect()->back()->with('error', 'You are not authorized to edit this post.');
        }

        // Pass the post data to the edit view
        return view('users.editblog', ['post' => $post, 'username' => $user->name,]);
    }

    public function update_blog_post(Request $request, $id)
    {
        // Find the post by its ID
        $post = Post::find($id);
    
        // Check if the post exists
        if (!$post) {
            // If the post does not exist, redirect back with an error message
            return redirect()->back()->with('error', 'Post not found.');
        }
    
        // Check if the currently authenticated user is the owner of the post
        if ($post->user_id !== auth()->id()) {
            // If the user is not the owner of the post, redirect back with an error message
            return redirect()->back()->with('error', 'You are not authorized to edit this post.');
        }
    
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:4096', // Validate image type and size
        ]);
    
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the previous image file if it exists
            if ($post->image) {
                Storage::disk('images')->delete($post->image);
            }
    
            // Upload and save the new image
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            Storage::disk('images')->put($imageName, file_get_contents($image), 'public');
    
            // Update the post with the new image path
            $post->image = $imageName;
        }
    
        // Update the post with the validated data
        $post->title = $validatedData['title'];
        $post->content = $validatedData['content'];
        $post->save();
    
        // Redirect back to the edit page with a success message
        return redirect()->route('user.dashboard')->with('success', 'Post updated successfully.');
    }
    
    public function user_posts()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Retrieve posts associated with the authenticated user
        $posts = Post::where('user_id', $user->id)->get();

        // Pass the posts to the view for display
        return view('users.userposts', compact('posts', 'user'));
    }
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
    
        // Check if the authenticated user is the owner of the post
        if (auth()->id() != $post->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to delete this post.');
        }
    
        // Delete the associated image if it exists
        if ($post->image) {
            Storage::disk('images')->delete($post->image);
        }
    
        // Delete the post
        $post->delete();
    
        return redirect()->route('userpost')->with('success', 'Post deleted successfully.');
    }
    
}
