<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Post;
use App\Models\GalleryImage;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function welcome()
    {
        // Fetch featured programs for the "hot" section/carousel
        $featuredPrograms = Program::where('is_featured', true)
            ->where('status', 'active')
            ->where('type', '!=', 'rolling')
            ->latest()
            ->take(5)
            ->get();

        // Fetch latest programs for the "Udemy style" section
        $latestPrograms = Program::where('status', 'active')
            ->where('type', '!=', 'rolling')
            ->latest()
            ->take(8)
            ->get();

        return view('welcome', compact('featuredPrograms', 'latestPrograms'));
    }

    public function explore(Request $request)
    {
        $query = Program::where('status', 'active')
            ->where('type', '!=', 'rolling');

        // Apply filters
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $programs = $query->latest()->paginate(9);

        return view('programs.explore', compact('programs'));
    }

    public function blog()
    {
        $featuredPost = Post::published()->latest()->first();
        $posts = Post::published()
            ->where('id', '!=', $featuredPost?->id)
            ->latest()
            ->paginate(9);

        return view('blog', compact('featuredPost', 'posts'));
    }

    public function blogShow($slug)
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();

        // Increment reads (simple way)
        $post->increment('reads');

        // Fetch related posts (same category)
        $relatedPosts = Post::published()
            ->where('category', $post->category)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(3)
            ->get();

        return view('blog-show', compact('post', 'relatedPosts'));
    }

    public function gallery(Request $request)
    {
        $query = GalleryImage::active()->latest();

        if ($request->ajax()) {
            $images = $query->paginate(12);
            return response()->json([
                'html' => view('partials.gallery-items', compact('images'))->render(),
                'hasMore' => $images->hasMorePages()
            ]);
        }

        $images = $query->paginate(12);

        return view('gallery', compact('images'));
    }
}
