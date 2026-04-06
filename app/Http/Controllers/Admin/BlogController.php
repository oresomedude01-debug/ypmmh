<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Notifications\NewBlogPostNotification;
use App\Notifications\NewBlogPublishedParentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('author')->latest()->paginate(10);
        return view('Admin.blogs.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'category' => 'required|string',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['author_id'] = auth()->id();
        $data['slug'] = Str::slug($request->title);
        $data['published_at'] = $request->status === 'published' ? now() : null;

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        $post = Post::create($data);

        // Notify Admins (always)
        $admins = User::role('Admin')->get();
        if ($admins->count() > 0) {
            Notification::send($admins, new NewBlogPostNotification($post));
        }

        // Notify Parents (only if published)
        if ($request->status === 'published') {
            $parents = User::role('Parent')->get();
            if ($parents->count() > 0) {
                Notification::send($parents, new NewBlogPublishedParentNotification($post));
            }
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $blog)
    {
        return view('Admin.blogs.show', ['post' => $blog]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $blog)
    {
        return view('Admin.blogs.edit', ['post' => $blog]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'category' => 'required|string',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        $justPublished = false;
        if ($request->status === 'published' && !$blog->published_at) {
            $data['published_at'] = now();
            $justPublished = true;
        }

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        $blog->update($data);

        // Notify Admins on update
        $admins = User::role('Admin')->get();
        if ($admins->count() > 0) {
            Notification::send($admins, new NewBlogPostNotification($blog, true));
        }

        // Notify Parents if just published (transitioned from draft)
        if ($justPublished) {
            $parents = User::role('Parent')->get();
            if ($parents->count() > 0) {
                Notification::send($parents, new NewBlogPublishedParentNotification($blog));
            }
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $blog)
    {
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post deleted successfully.');
    }
}
